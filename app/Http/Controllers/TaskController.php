<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function index(TodoList $todo_list)
    {
        $tasks = $todo_list->tasks;
    	return response($tasks);
    }

    public function store(Request $request, TodoList $todo_list)
    {
        $request->validate([
            'title' => [
                'required',
            ],
        ]);

        $task = $todo_list->tasks()->create([
            'title' => $request->title,
        ]);

        return response($task, Response::HTTP_CREATED);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response(
            status: Response::HTTP_NO_CONTENT
        );
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => [
                'required',
            ],
        ]);
        $task->update([
            'title' => $request->title,
        ]);
        return response($task);
    }
}
