<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Todo;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    public function store(Request $request) {
       
        if (!$request->task) {
            return response()->json([
                'error' => 'Todo task is required'
            ], 400);
        }
      
        

        Todo::create([
            'title' => $request->task,
            'user_id' => $request->user()->id
        ]);
        
        return response()->json([
            'success' => 'Todo successfully added',
            'todos' => $this->get_todos($request)
        ]);
        
    }



    public function mark_todo(Request $request) {
        $validator = Validator::make($request->all(), [
            'todo_id' => 'required|exists:todos,id'
        ], [
            'todo_id.required' => 'Todo ID is missing',
            'todo_id.exists' => 'Todo item not found'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $todo = Todo::find($request->todo_id);
        
        $todo->complete = !$todo->complete;
        $todo->save();

        return response()->json([
            'success' => 'Task successfully marked as '. ($todo->complete? 'COMPLETED' : 'PENDING')
        ]);
    }


    public function get_todos(Request $request) {
        $query = Todo::query();
        $query->where('user_id', $request->user()->id);
        
        if ($request->sort && in_array(strtolower($request->sort), ['asc', 'desc'])) {
            $query->orderBy('completed', $request->sort);
        }

        $todos = $query->latest()->paginate(5)->map(function($todo){
            $todo->time_created = timeago($todo->created_at);
            return $todo;
        });
        return $todos;
    }

    public function delete_todo(Request $request) {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:todos,id'
        ], [
            'task_id.required' => 'Task id is missing',
            'task_id.exists' => 'Task has already been deleted',
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $todo = Todo::find($request->task_id);
        $todo->delete();

        return response()->json([
            'success' => 'Task deleted successfully',
            'todos' => $this->get_todos($request)
        ]);
    }
}
