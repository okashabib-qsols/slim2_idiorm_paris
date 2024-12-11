<?php

use Slim\Slim;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config.php';

$app = new Slim();

// routes
$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

$app->run();