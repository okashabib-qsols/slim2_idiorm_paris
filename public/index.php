<?php

use Slim\Slim;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config.php';

$app = new Slim([
    'log.enabled' => true,
    'log.level' => \Slim\Log::DEBUG,
    'log.writer' => new \Slim\LogWriter(fopen(__DIR__ . '/../logs/app.log', 'a')),
]);

// views
$app->view(new \Slim\View());
$app->view()->setTemplatesDirectory(__DIR__ . '/../src/Templates');

// routes
$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

$app->run();