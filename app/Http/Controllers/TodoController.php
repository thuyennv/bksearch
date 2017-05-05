<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Todo\TodoRepository;

class TodoController extends Controller
{
    private $todo;

    public function __construct(TodoRepository $todo)
    {
        $this->todo = $todo;
    }

    public function getAllTodos()
    {
        return $this->todo->getAll();
    }
}
