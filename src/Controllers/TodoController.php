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
        try {
            $this->app->log->info("Fetching Todos");

            $todos = Todo::order_by_asc('item_position')->find_array();

            if (!empty($todos)) {
                $this->app->render('todo/todo.twig', [
                    'success' => true,
                    'message' => 'Got resource successfully',
                    'data' => $todos,
                    'title' => "Todo App"
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Data not found'
                ]);
                $this->app->response->header('Content-Type', 'application/json');
                $this->app->log->warning("No todos found.");
            }
        } catch (\Exception $e) {
            $this->app->log->error('Error while fetching todos: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while fetching todos.'
            ]);
            $this->app->response->header('Content-Type', 'application/json');
            $this->app->response->setStatus(500);
        }
    }

    public function show($id)
    {
        try {
            if (!is_numeric($id)) {
                echo json_encode([
                    'success' => false,
                    'message' => "ID must be a number. `$id` is given."
                ]);
                $this->app->response->header('Content-Type', 'application/json');
                $this->app->log->warning("Invalid ID format: $id is not numeric");
                return;
            }

            $todo = Todo::find_one($id);

            if (!empty($todo)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Got resource successfully',
                    'data' => $todo->as_array()
                ]);
                $this->app->response->header('Content-Type', 'application/json');
                $this->app->log->info("Todo found with ID: $id");
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Todo not found.'
                ]);
                $this->app->response->header('Content-Type', 'application/json');
                $this->app->log->warning("Todo not found with ID: $id");
            }
        } catch (\Exception $e) {
            $this->app->log->error("Error while fetching todo with ID: $id. Error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while fetching the todo.'
            ]);
            $this->app->response->header('Content-Type', 'application/json');
            $this->app->response->setStatus(500);
        }
    }

    public function store()
    {
        try {
            $data = $this->app->request->post();

            if (empty(trim($data['description']))) {
                $response = [
                    'success' => false,
                    'message' => 'Description is required.'
                ];
                echo json_encode($response);
                $this->app->response->setStatus(400);
                $this->app->response->header('Content-Type', 'application/json');
                $this->app->log->warning("Description is required.");
                return;
            }

            $position = Todo::max('item_position') + 1;
            $todo = Todo::create([
                'description' => $data['description'],
                'item_position' => $position
            ]);

            if ($todo) {
                $response = [
                    'success' => true,
                    'message' => 'Todo added successfully.',
                    'data' => $todo->as_array()
                ];
                $todo->save();
                $this->app->response->header('Content-Type', 'application/json');
                $this->app->response->setStatus(201);
                echo json_encode($response);
                $this->app->log->info("Todo added successfully.");
            }
        } catch (\Exception $e) {
            $this->app->log->error("Error while adding todo: " . $e->getMessage());
            $response = [
                'success' => false,
                'message' => 'An error occurred while adding the todo.'
            ];
            $this->app->response->header('Content-Type', 'application/json');
            $this->app->response->setStatus(500);
            echo json_encode($response);
        }
    }

    public function update($id)
    {
        try {
            $data = $this->app->request->post();

            if (!is_numeric($id)) {
                echo json_encode([
                    'success' => false,
                    'message' => "ID must be a number. `$id` is given."
                ]);
                $this->app->response->header('Content-Type', 'application/json');
                $this->app->log->warning("Invalid ID format: $id is not numeric");
                exit;
            }

            $todo = Todo::find_one($id);

            if (!empty($todo)) {
                if (isset($data['description'])) {
                    $todo->description = $data['description'];
                }
                if (isset($data['color'])) {
                    $todo->color = $data['color'];
                }
                if (isset($data['is_done'])) {
                    $todo->is_done = $data['is_done'];
                }

                $todo->save();

                echo json_encode([
                    'success' => true,
                    'message' => 'Todo updated successfully.',
                    'data' => $todo->as_array()
                ]);
                $this->app->response->header('Content-Type', 'application/json');
                $this->app->log->info("Todo updated successfully.");
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Todo not found.'
                ]);
                $this->app->log->warning("Todo not found with ID: $id");
            }
        } catch (\Exception $e) {
            $this->app->log->error("Error while updating todo: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while updating the todo.'
            ]);
            $this->app->response->header('Content-Type', 'application/json');
            $this->app->response->setStatus(500);
        }
    }

    public function update_position()
    {
        try {
            $data = $this->app->request->post('position');

            if (!$data) {
                echo json_encode([
                    'success' => false,
                    'message' => 'No positions provided.'
                ]);
                $this->app->response->header('Content-Type', 'application/json');
                $this->app->log->warning("No positions provided.");
                return;
            }

            foreach ($data as $item) {
                $todo = Todo::find_one($item['id']);
                if ($todo) {
                    $todo->item_position = $item['position'];
                    $todo->save();
                } else {
                    $this->app->log->warning("Todo not found with ID: {$item['id']} for position update.");
                }
            }

            echo json_encode([
                'success' => true,
                'message' => 'Positions updated successfully.'
            ]);
            $this->app->response->header('Content-Type', 'application/json');
            $this->app->log->info("Positions updated successfully.");
        } catch (\Exception $e) {
            $this->app->log->error("Error while updating positions: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while updating positions.'
            ]);
            $this->app->response->header('Content-Type', 'application/json');
            $this->app->response->setStatus(500);
        }
    }

    public function delete($id)
    {
        try {
            if (!is_numeric($id)) {
                echo json_encode([
                    'success' => false,
                    'message' => "ID must be a number. `$id` is given."
                ]);
                $this->app->response->header('Content-Type', 'application/json');
                $this->app->log->warning("Invalid ID format: $id is not numeric");
                return;
            }

            $todo = Todo::find_one($id);

            if (!empty($todo)) {
                $todo->delete();
                echo json_encode([
                    'success' => true,
                    'message' => 'Todo deleted successfully.'
                ]);
                $this->app->response->header('Content-Type', 'application/json');
                $this->app->log->info("Todo deleted successfully.", ['id' => $id]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Todo not found.'
                ]);
                $this->app->response->header('Content-Type', 'application/json');
                $this->app->log->warning("Todo not found with ID: $id");
            }
        } catch (\Exception $e) {
            $this->app->log->error("Error while deleting todo: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while deleting the todo.'
            ]);
            $this->app->response->header('Content-Type', 'application/json');
            $this->app->response->setStatus(500);
        }
    }
}