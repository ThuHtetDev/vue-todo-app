<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/todos','TodoController@index')->name('getTodos');
Route::post('/todos','TodoController@createTodo')->name('createTodo');
Route::put('/todos/{id}','TodoController@updateTodo')->name('updateTodo');
Route::put('/todosCheckAll','TodoController@checkAll')->name('checkAll');
Route::delete('/todos/{id}','TodoController@deleteTodo')->name('deleteTodo');