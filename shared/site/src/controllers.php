<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use app\models\Course as CourseModel;
use app\models\Partner as PartnersModel;

// @service for Courses model
$app['courses.model'] = $app->share(function () use ($app) {
    return new CourseModel($app);
});

// @service for Partners model
$app['partners.model'] = $app->share(function () use ($app) {
    return new PartnersModel($app);
});

// @route landing page
$app->match('/', function () use ($app) {
    return $app['twig']->render('landing/index.html.twig', array(
        'courses' => $app['courses.model']->getAll(),
        'partners' => $app['partners.model']->getAll(),
    ));
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

    return $app['twig']->render('course/index.html.twig', array(
        'course' => $course,
    ));
})
->bind('course');

// @route update db changes
$app->match('/admin/update', function () use ($app) {
    echo time().'<br>';

    $courses = $app['courses.model']->update();
    echo sprintf('%s = %d<br>', 'courses', count($courses));

    $partners = $app['partners.model']->update();
    echo sprintf('%s = %d<br>', 'partners', count($partners));

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