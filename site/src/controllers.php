<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// 
$app->before(function (Request $request, Application $app) {
    $url = $request->getUri();
    $redirects = $app['redirects.model']->getAll();

    foreach ($redirects as $redirect) {
        if (strpos($url, $redirect['search']) !== false) {
            return $app->redirect($redirect['redirect_to'], $redirect['code']);
        }
    }
}, Application::EARLY_EVENT);

// @route landing page
$app->match('/', function () use ($app) {
    $host = $app['host.service']->parse();

    if ($host) {
        $page = (array) $app['pages.model']->findAndMerge('page', array(
            'landing',
            'promo',
            'promo '.$host->key,
        ));
    }
    else {
        $page = $app['pages.model']->findBy('page', 'landing');
    }

    $params = array(
        'courses' => $app['courses.model']->getAll(),
        'workshops' => $app['workshops.model']->getAll(),
        'partners' => $app['partners.model']->getAll(),
        'teachers' => $app['teachers.model']->getAll(),
        'studentsCompanies' => $app['studentsCompanies.model']->getAll(),
        'title' => $page['title'],
        'meta_keywords' => $page['meta keywords'],
        'meta_description' => $page['meta description'],
        'meta_author' => $page['meta author'],
        'promo_key' => $host ? $host->key : null,
    );

    if ($host) {
        $template = 'promo/'.$host->key.'/index.html.twig';
    }
    else {
        $template = 'landing/index.html.twig';
    }
    
    if (isset($_GET['debug']) && $app['debug']) {
        var_dump($template, $params);
        die;
    }

    return $app['twig']->render($template, $params);
})
->bind('landing');

// @route course page
$app->match('/course/{id}', function (Request $request) use ($app) {
    $courseId = $request->get('id');
    $course = $app['courses.model']->findBy('id', $courseId);

    if (!$course) {
        $normalizedCourseId = str_replace('-', ' ', $courseId);
        $errorMessage = sprintf('requested course with name "%s" has been not found', $normalizedCourseId);

        $app->abort(404, $errorMessage);
    }

    $page = $app['pages.model']->findBy('page', 'course '.$courseId);
    $coursePlan = $app['coursesPlan.model']->formatCoursePlan($courseId);

    $course['teachers'] = $app['teachers.model']->filterByCourseGroup($course['group']);

    if (isset($_GET['debug']) && $app['debug']) {
        var_dump($course);
        die;
    }

    return $app['twig']->render('course/index.html.twig', array(
        'course' => $course,
        'title' => $page['title'],
        'coursePlan' => $coursePlan,
        'meta_keywords' => $page['meta keywords'],
        'meta_description' => $page['meta description'],
        'meta_author' => $page['meta author'],
    ));
})
->bind('course');

// @route workshop page
$app->match('/workshop/{id}', function (Request $request) use ($app) {
    $workshopId = $request->get('id');
    $workshop = $app['workshops.model']->findBy('id', $workshopId);

    if (!$workshop) {
        $normalizedId = str_replace('-', ' ', $workshopId);
        $errorMessage = sprintf('requested workshop with name "%s" has been not found', $normalizedId);

        $app->abort(404, $errorMessage);
    }

    $page = $app['pages.model']->findAndMerge('page', array(
        'landing',
        'workshop',
        'workshop '.$workshopId,
    ), array(
        'title' => $workshop['title'] . ' | CURSOR Education',
        'meta keywords' => join(', ', array(
            $workshop['title'],
            $workshop['desc'],
        )),
        'meta description' => $workshop['desc'],
        'meta author' => $workshop['title'],
    ));

    if (isset($_GET['debug']) && $app['debug']) {
        var_dump($course);
        die;
    }

    return $app['twig']->render('workshop/index.html.twig', array(
        'workshop' => $workshop,
        'title' => $page['title'],
        'meta_keywords' => $page['meta keywords'],
        'meta_description' => $page['meta description'],
        'meta_author' => $page['meta author'],
    ));
})
->bind('workshop');

// @route teacher profile page
$app->match('/teacher/{id}', function (Request $request) use ($app) {
    $teacherId = $request->get('id');
    $teacher = $app['teachers.model']->findBy('id', $teacherId);

    if (!$teacher) {
        return $app->redirect('/');
    }

    // set default values for meta
    $page = $app['pages.model']->findAndMerge('page', array(
        'landing',
        'teacher',
        'teacher '.$teacherId,
    ), array(
        'title' => $teacher['name'] . ' | CURSOR Education',
        'meta keywords' => join(', ', array(
            $teacher['name'],
            $teacher['position'],
            join(', ', $teacher['courses'])
        )),
        'meta description' => join('. ', array(
            $teacher['desc_short'],
        )),
        'meta author' => $teacher['name'],
    ));

    $page['title'] = str_replace(':teacher-name', $teacher['name'], $page['title']);

    if (isset($_GET['debug']) && $app['debug']) {
        var_dump($page, $teacher);
        die;
    }

    return $app['twig']->render('teacher/index.html.twig', array(
        'teacher' => $teacher,
        'title' => $page['title'],
        'meta_keywords' => $page['meta keywords'],
        'meta_description' => $page['meta description'],
        'meta_author' => $page['meta author'],
    ));
})
->bind('teacher');

// @route submit form from landing page
$app->post('/callme', function (Request $request) use ($app) {
    $order = $app['order.service']->newOrder();
    $order->source = $request->get('source', 'unknown');
    $order->name = $request->get('name');
    $order->email = $request->get('email');
    $order->phone = $request->get('phone');

    $app['order.service']->add($order);

    sleep(2);

    return 'ok';
})
->bind('order-form');

// @route teacher profile page
$app->match('/teacher/update/{secret}', function (Request $request) use ($app) {
    $teacherSecret = $request->get('secret');

    $teacher = $app['teachers.model']->findBy('update_secret', $teacherSecret);
    $teacher = $app['teachers.model']->formatRecord($teacher);

    if (empty($teacher['id'])) {
        var_dump('404');die;
    }

    $app['teachers.model']->setByKey($teacher['id'], $teacher);

    $teacherUrl = $app['url_generator']->generate('teacher', array('id' => $teacher['id']));
    return $app->redirect($teacherUrl);
});

// @route to test email sending
$app->match('/admin/test-email/', function (Request $request) use ($app) {
    $_REQUEST['debug'] = $app['config.model']->getByKey('debug.secret');

    $email = $app['email.service']->newEmail();
    $email->subject = 'test';

    $email->to = array();
    $email->to['itspoma@gmail.com'] = 'test';

    $email->body = 'testing';

    var_dump($app['email.service']->send($email));
    die;
});

// @route test trello service
$app->match('/admin/test-trello/', function (Request $request) use ($app) {
    $_REQUEST['debug'] = $app['config.model']->getByKey('debug.secret');

    $order = $app['order.service']->newOrder();
    $order->source = 'landing';
    $order->name = 'Jozephinee';
    $order->email = 'joze@gmail.com';
    $order->phone = '355059929';

    $ok = $app['order.service']->addToTrello($order);
    var_dump('add-to-trello', $ok);
    die;
});

// @route update db changes
$app->match('/admin/update/', function (Request $request) use ($app) {
    $list = array(
        'all',
        'config',
        'workshops',
        'courses',
        'coursesTechnologies',
        'technologies',
        'coursesPlan',
        'partners',
        'studentsCompanies',
        'pages',
        'teachers',
        'teachersCourses',
        'teachersLinks',
        'redirects',
    );

    $updateWhat = $request->get('what');

    if (in_array($updateWhat, $list)) {
        $updateKeys = array();

        if ($updateWhat == 'all') {
            $updateKeys = $list;
            unset($updateKeys[0]);
        }
        else {
            $updateKeys = array($updateWhat);
        }

        echo time().'<br>';

        foreach ($updateKeys as $updateKey) {
            $result = $app[$updateKey.'.model']->update();
            echo sprintf('%s = %d<br>', $updateKey, count($result));
        }
    }

    return $app['twig']->render('admin/update.html.twig', array(
        'list' => $list,
    ));
});

//
$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    return $app['twig']->render('error/index.html.twig', array(
        'code' => $code,
        'message' => $e->getMessage(),
    ));
});

return $app;
