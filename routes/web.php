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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/threads', 'ThreadController@index')->name('threads.index');
Route::get('/threads/{channel}/{thread}', 'ThreadController@show')->name('threads.show');
Route::get('/threads/create', 'ThreadController@create')->name('threads.create');
Route::post('/threads', 'ThreadController@store')->name('threads.store');

Route::get('/threads/{channel}', 'ChannelController@show')->name('channel.show');

Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store')->name('reply.store');
Route::post('/replies/{reply}/favorite', 'FavoriteController@store')->name('reply.favorite');
