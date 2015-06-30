<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    $course = $app['courses.model']->mapTechnologies($course, $app['technologies.model']->getAll());

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

    $page = $app['pages.model']->findBy('page', 'teacher');
    $teacher = $app['teachers.model']->findBy('id', $teacherId);

    return $app['twig']->render('teacher/index.html.twig', array(
        'teacher' => $teacher,
    ));
})
->bind('teacher');

// @route submit form from landing page
$app->post('/callme', function (Request $request) use ($app) {
    $filename = ROOT_DIR.'/web/'.$app['form.file'];

    $data = array(
        $request->get('name'),
        $request->get('phone'),
    );

    $f = fopen($filename, 'aw');
    fwrite($f, join("\t", $data)."\n");
    fclose($f);

    sleep(2);

    return 'ok';
})
->bind('landing-form');

// @route submit form from course page
$app->post('/callme-course', function (Request $request) use ($app) {
    $filename = ROOT_DIR.'/web/'.$app['form.file'];

    $data = array(
        $request->get('course'),
        $request->get('name'),
        $request->get('phone'),
    );

    $f = fopen($filename, 'aw');
    fwrite($f, join("\t", $data)."\n");
    fclose($f);

    sleep(2);

    return 'ok';
})
->bind('course-form');

// @route update db changes
$app->match('/admin/update/', function (Request $request) use ($app) {
    $list = array(
        'all',
        'config',
        'courses',
        'technologies',
        'coursesPlan',
        'partners',
        'studentsCompanies',
        'pages',
        'teachers'
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