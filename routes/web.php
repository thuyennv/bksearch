<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('my-todos', 'TodoController@getAllTodos');
Route::get('add', 'TodoController@getAllTodos');
Route::get('index', 'SearchController@index');
Route::get('test', 'SearchController@test');

