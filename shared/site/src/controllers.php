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
    $page = $app['pages.model']->findBy('page', 'landing');

    $params = array(
        'courses' => $app['courses.model']->getAll(),
        'partners' => $app['partners.model']->getAll(),
        'teachers' => $app['teachers.model']->getAll(),
        'studentsCompanies' => $app['studentsCompanies.model']->getAll(),
        'title' => $page['title'],
        'meta_keywords' => $page['meta keywords'],
        'meta_description' => $page['meta description'],
        'meta_author' => $page['meta author'],
    );

    if (isset($_GET['debug']) && $app['debug']) {
        var_dump($params);
        die;
    }

    return $app['twig']->render('landing/index.html.twig', $params);
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

// @route teacher profile page
$app->match('/teacher/{id}', function (Request $request) use ($app) {
    $teacherId = $request->get('id');
    $teacher = $app['teachers.model']->findBy('id', $teacherId);

    if (!$teacher) {
        return $app->redirect('/');
    }

    $page = $app['pages.model']->findBy('page', 'teacher '.$teacherId);
    
    // set default values for meta
    $page = array_merge(array(
        'title' => $teacher['name'] . ' | CURSOR.education',

        'meta keywords' => join(', ', array(
            $teacher['name'],
            $teacher['position'],
            join(', ', $teacher['courses'])
        )),

        'meta description' => join('. ', array(
            $teacher['desc_short'],
        )),

        'meta author' => $teacher['name'],
    ), (array) $page);

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
    $filename = ROOT_DIR.'/web/'.$app['config.model']->getByKey('reg-form.file');

    $data = array(
        date('Y-m-d H:i:s'),
        'landing',
        $request->get('name'),
        $request->get('phone'),
    );

    $f = fopen($filename, 'aw');
    fwrite($f, join("\t\t", $data)."\n");
    fclose($f);

    sleep(2);

    return 'ok';
})
->bind('landing-form');

// @route submit form from course page
$app->post('/callme-course', function (Request $request) use ($app) {
    $filename = ROOT_DIR.'/web/'.$app['config.model']->getByKey('reg-form.file');

    $data = array(
        date('Y-m-d H:i:s'),
        $request->get('course'),
        $request->get('name'),
        $request->get('phone'),
    );

    $f = fopen($filename, 'aw');
    fwrite($f, join("\t\t", $data)."\n");
    fclose($f);

    sleep(2);

    return 'ok';
})
->bind('course-form');

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

// @route update db changes
$app->match('/admin/update/', function (Request $request) use ($app) {
    $list = array(
        'all',
        'config',
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