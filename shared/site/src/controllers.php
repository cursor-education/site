<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use app\models\Config as ConfigModel;

use app\models\Course as CourseModel;
use app\models\Technologies as TechnologiesModel;
use app\models\CoursePlan as CoursePlanModel;
use app\models\Partner as PartnersModel;
use app\models\StudentsCompanies as StudentsCompaniesModel;
use app\models\Pages as PagesModel;
use app\models\Teachers as TeachersModel;

// @service for Config model
$app['config.model'] = $app->share(function () use ($app) {
    return new ConfigModel($app);
});

// @service for Courses model
$app['courses.model'] = $app->share(function () use ($app) {
    return new CourseModel($app);
});

// @service for Technologies model
$app['technologies.model'] = $app->share(function () use ($app) {
    return new TechnologiesModel($app);
});

// @service for Courses model
$app['coursesPlan.model'] = $app->share(function () use ($app) {
    return new CoursePlanModel($app);
});

// @service for Partners model
$app['partners.model'] = $app->share(function () use ($app) {
    return new PartnersModel($app);
});

// @service for StudentsCompanies model
$app['studentsCompanies.model'] = $app->share(function () use ($app) {
    return new StudentsCompaniesModel($app);
});

// @service for Pages model
$app['pages.model'] = $app->share(function () use ($app) {
    return new PagesModel($app);
});

// @service for Teachers model
$app['teachers.model'] = $app->share(function () use ($app) {
    return new TeachersModel($app);
});

// @route landing page
$app->match('/', function () use ($app) {
    $page = $app['pages.model']->findBy('page', 'landing');

    $enabledTechers = $app['config.model']->getByKey('enable.teachers') !== 'no';

    $params = array(
        'courses' => $app['courses.model']->getAll(),
        'partners' => $app['partners.model']->getAll(),
        'teachers' => $app['teachers.model']->getAll(),
        'studentsCompanies' => $app['studentsCompanies.model']->getAll(),
        'title' => $page['title'],
        'meta_keywords' => $page['meta keywords'],
        'meta_description' => $page['meta description'],
        'meta_author' => $page['meta author'],
        'enable_teachers' => $enabledTechers,
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
    $enabledTechers = $app['config.model']->getByKey('enable.teachers') !== 'no';

    if (!$enabledTechers) {
        return $app->redirect('/');
    }

    $teacherId = $request->get('id');

    $page = $app['pages.model']->findBy('page', 'teacher');
    $pageTeacher = $app['pages.model']->findBy('page', 'teacher '.$teacherId);

    $teacher = $app['teachers.model']->findBy('id', $teacherId);

    // foreach ($page as $pageKey => $pageValue) {
    //     foreach ($teacher as $key => $value) {
    //         $page[$pageKey] = str_replace(':teacher-'.$key, $value, $pageValue);
    //     }
    // }

    var_dump($page, $teacher);die;
    var_dump($teacher);die;

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
$app->match('/admin/update', function () use ($app) {
    echo time().'<br>';

    $config = $app['config.model']->update();
    echo sprintf('%s = %d<br>', 'config', count($config));

    $courses = $app['courses.model']->update();
    echo sprintf('%s = %d<br>', 'courses', count($courses));

    $technologies = $app['technologies.model']->update();
    echo sprintf('%s = %d<br>', 'technologies', count($technologies));

    $coursesPlan = $app['coursesPlan.model']->update();
    echo sprintf('%s = %d<br>', 'courses-plan', count($coursesPlan));

    $partners = $app['partners.model']->update();
    echo sprintf('%s = %d<br>', 'partners', count($partners));

    $studentsCompanies = $app['studentsCompanies.model']->update();
    echo sprintf('%s = %d<br>', 'studentsCompanies', count($studentsCompanies));

    $pages = $app['pages.model']->update();
    echo sprintf('%s = %d<br>', 'pages', count($pages));

    $teachers = $app['teachers.model']->update();
    echo sprintf('%s = %d<br>', 'teachers', count($teachers));    

    return '';
});

//
$app->error(function (\Exception $e, $code) use ($app) {
    // if ($app['debug']) {
    //     return;
    // }

    return $app['twig']->render('error/index.html.twig', array(
        'code' => $code,
        'message' => $e->getMessage(),
    ));
});

return $app;