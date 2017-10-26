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


Route::get('/', 'TwitterController@index');


Route::post('/search_tweets', 'TwitterController@searchUserTweets');
Route::get('/search_user', 'TwitterController@searchUser');
//Route::get('/twitter-feed', 'TwitterController@index');
