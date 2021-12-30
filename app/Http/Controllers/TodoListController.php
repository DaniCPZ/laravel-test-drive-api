<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoListRequest;
use App\Models\TodoList;
use Auth;
use Illuminate\Http\Response;

class TodoListController extends Controller
{
    public function index()
    {
        $lists = Auth::user()->todo_lists;
        return response($lists);
    }

    public function show(TodoList $todo_list)
    {
        return response($todo_list);
    }

    public function store(TodoListRequest $request)
    {
        $list = Auth::user()
            ->todo_lists()
            ->create($request->validated());

    	return response($list, 201);
    }

    public function update(TodoListRequest $request, TodoList $todo_list)
    {
        $todo_list->update($request->validated());
        return $todo_list;
    }

    public function destroy(TodoList $todo_list)
    {
        $todo_list->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }
}
