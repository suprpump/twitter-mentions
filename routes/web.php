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

// entry route
Route::get('/', 'TwitterController@index');

// gets tweets of specified user
Route::post('/search_tweets', 'TwitterController@searchUserTweets');

// search for single user
Route::get('/search_user', 'TwitterController@searchUser');
//Route::get('/twitter-feed', 'TwitterController@index');
