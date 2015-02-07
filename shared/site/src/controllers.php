<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->match('/', function () use ($app) {
    return $app['twig']->render('landing/index.html.twig', array(
        'courses' => $app['cache']->fetch('db.google.courses.data'),
        'partners' => $app['cache']->fetch('db.google.partners.data'),
    ));
})
->bind('landing');

$app->match('/course/{id}', function () use ($app) {
    var_dump('course');
})
->bind('course');

// move to helpers
$app['google-api-fetch'] = $app->protect(function ($key) use ($app) {
    $url = sprintf(
        'https://docs.google.com/spreadsheets/d/%s/export?gid=%s&format=%s',
        $app['db.google.'.$key.'.key'],
        $app['db.google.'.$key.'.gid'],
        'tsv'
    );

    $csv = trim(file_get_contents($url));
    $csv = split("\n", $csv);

    $columns = split("\t", $csv[0]);
    $records = array();

    for ($i = 1; $i < count($csv); $i++) {
        $row = split("\t", $csv[$i]);
        $records[] = array_combine($columns, $row);
    }

    $app['cache']->store('db.google.'.$key.'.data', $records);

    return $records;
});

$app->match('/admin/update', function () use ($app) {
    $records = $app['google-api-fetch']('courses');
    $partners = $app['google-api-fetch']('partners');
    //

    var_dump($records);die;

    return 'test';
});

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message, $code);
});

return $app;