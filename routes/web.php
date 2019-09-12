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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('redis');
});

Route::get('/redis', 'RedisController@test1');
Route::get('/redis2', 'RedisController@test2');
Route::get('/redis3', 'RedisController@test3');

Route::get('/{id}/{content}', 'RedisController@set');


Route::get('test', 'TestController@test');