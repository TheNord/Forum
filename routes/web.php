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
Route::post('/threads', 'ThreadController@store')->name('threads.store')->middleware('check.email.confirmed');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy')->name('threads.delete');

Route::get('/threads/{channel}', 'ChannelController@show')->name('channel.show');

Route::get('/threads/{channel}/{thread}#reply-{reply}', 'ThreadController@show')->name('reply.show');
Route::get('/thread/{thread}/replies', 'ReplyController@getReplies');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store')->name('reply.store');
Route::put('/reply/{reply}', 'ReplyController@update')->name('reply.update');
Route::delete('/reply/{reply}', 'ReplyController@destroy')->name('reply.delete');

Route::post('/threads/{channel}/{thread}/subscribe', 'SubscribeThreadController@store')->name('thread.subscribe');
Route::delete('/threads/{channel}/{thread}/unsubscribe', 'SubscribeThreadController@destroy')->name('thread.unsubscribe');

Route::post('/replies/{reply}/favorite', 'FavoriteController@store')->name('reply.favorite');
Route::delete('/replies/{reply}/favorite', 'FavoriteController@destroy')->name('reply.unFavorite');

Route::post('/replies/{reply}/best', 'ReplyController@markAsBest')->name('reply.best.mark');
Route::delete('/replies/{reply}/best', 'ReplyController@unMarkAsBest')->name('reply.best.unMark');

Route::get('/user/{user}/show', 'UserController@profile')->name('user.profile');

Route::get('/profile/edit', 'UserController@edit')->name('user.profile.edit');
Route::put('/profile/edit', 'UserController@update')->name('user.profile.update');

Route::get('/notifications', 'UserNotificationsController@fetch')->name('notifications');
Route::delete('/notifications/{notification}/read', 'UserNotificationsController@destroy')->name('notifications.read');

Route::get('/api/users', 'Api\UsersController@index');

Route::get('/register/confirm', 'Auth\RegisterController@confirm')->name('register.confirm');