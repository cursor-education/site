<?php
define('ROOT_DIR', __DIR__.'/..');
define('SRC_DIR', ROOT_DIR.'/src');

define('ENV_PRODUCTION', 'production');

date_default_timezone_set('Europe/Kiev');

require_once ROOT_DIR.'/vendor/autoload.php';
$app = new Silex\Application();

$env = getenv('APP_ENV') ?: ENV_PRODUCTION;

if ($env != ENV_PRODUCTION) {
    Symfony\Component\Debug\Debug::enable();
}

require SRC_DIR.'/app.php';
require SRC_DIR.'/services.php';
require SRC_DIR.'/controllers.php';

if ($env == ENV_PRODUCTION) {
    $app['http_cache']->run();
}
else {
    $app->run();
}