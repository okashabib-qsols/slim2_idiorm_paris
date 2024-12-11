<?php

return function ($app) {
    $app->get('/', function () use ($app) {
        $data = ['name' => 'Slim 2', 'type' => 'Micro FrameWork'];
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode($data);
    });
};