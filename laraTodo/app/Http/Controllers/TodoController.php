<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use Validator;

class TodoController extends Controller
{
    public function index(){
        $todos = Todo::all();
        return response()->json($todos);
    }
    public function createTodo(Request $request){
        $data = $request->validate([
            'title' => 'required'
        ]);
        $createTodo = Todo::create($data);
        return response()->json($createTodo,200);
    }
    public function updateTodo(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        $updateTodo = Todo::find($id);
        $updateTodo->title = $request['title'];
        $updateTodo->completed = $request['completed'];
        $updateTodo->save();
        return response()->json($updateTodo,200);
    }
    public function deleteTodo($id){
        $deleteTodo = Todo::find($id);
        $deleteTodo->delete();
        return response()->json("Delete Successfully",200);;
    }
    public function checkAll(Request $request){
        $data = $request->validate([
            'completed' => 'required|boolean'
        ]);
        Todo::query()->update($data);
        return response()->json('updated all',200);
    }
}
