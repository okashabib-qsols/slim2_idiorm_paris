<?php

use App\Controllers\TodoController;

return function ($app) {
    $app->notFound(function () use ($app) {
        $app->render('error/404.twig');
    });

    $app->get('/', function () use ($app) {
        $data = ['name' => 'Slim 2', 'type' => 'Micro FrameWork'];
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode($data);
    });

    $app->get('/todos', function () use ($app) {
        $controller = new TodoController($app);
        $controller->index();
    });
    
    $app->get('/todos/:id', function ($id) use ($app) {
        $controller = new TodoController($app);
        $controller->show($id);
    });
};