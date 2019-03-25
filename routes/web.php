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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'ThreadController@index')->name('threads.index');
Route::get('/threads/{channel}/{thread}', 'ThreadController@show')->name('threads.show');
Route::get('/threads/create', 'ThreadController@create')->name('threads.create');
Route::post('/threads', 'ThreadController@store')->name('threads.store');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy')->name('threads.delete');

Route::get('/threads/{channel}', 'ChannelController@show')->name('channel.show');

Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store')->name('reply.store');
Route::put('/reply/{reply}', 'ReplyController@update')->name('reply.update');
Route::delete('/threads/{channel}/{thread}/reply/{reply}', 'ReplyController@destroy')->name('reply.delete');
Route::post('/replies/{reply}/favorite', 'FavoriteController@store')->name('reply.favorite');
Route::get('/threads/{channel}/{thread}#reply-{reply}', 'ThreadController@show')->name('reply.show');

Route::get('/profile/{user}', 'UserController@profile')->name('user.profile');