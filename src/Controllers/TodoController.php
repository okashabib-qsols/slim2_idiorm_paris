<?php

namespace App\Controllers;
use App\Models\Todo;

class TodoController
{
    protected $app;
    public function __construct($app)
    {
        $this->app = $app;
    }
    public function index()
    {
        $todos = Todo::order_by_asc('item_position')->find_array();
        $this->app->render('todo/todo.twig', ['data' => $todos]);
        $this->app->log->info(date('d-m-Y-D H:i:s') . " Fetched Todos");
    }
    public function show($id)
    {
        $todos = Todo::find_one($id);
        if (!$todos) {
            echo json_encode(['error' => 'Todo Not Found']);
        }
        $this->app->response->header('Content-Type', 'application/json');
        echo json_encode($todos->as_array());
    }

    public function test()
    {
        $todos = Todo::order_by_asc('item_position')->find_many();
        $this->app->render('home.twig', ['data_description' => $todos->description]);
    }
}