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

Route::post('login','ApiController@login')->name('login');
Route::get('getPosts','ApiController@getPosts')->name('getPosts');
Route::get('deletePost/{id}','ApiController@deletePost')->name('deletePost');
Route::get('getComments/{id}','ApiController@getComments')->name('getComments');
Route::get('deleteComment/{commentId}/{postId}','ApiController@deleteComment')->name('deleteComment');
