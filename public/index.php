<?php

use Slim\Slim;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config.php';

if (!function_exists('get_magic_quotes_gpc')) {
    function get_magic_quotes_gpc()
    {
        return false;
    }
}

$app = new Slim([
    'log.enabled' => true,
    'log.level' => \Slim\Log::DEBUG,
    'log.writer' => new \Slim\LogWriter(fopen(__DIR__ . '/../logs/app.log', 'a')),
    'debug' => true,
]);

// views
$app->view(new \Slim\View());
$app->view()->setTemplatesDirectory(__DIR__ . '/../src/Templates');

// routes
$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

$app->run();