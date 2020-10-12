<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('user', 'UserController@register');
Route::post('auth', 'UserController@authenticate');
Route::post('users', 'UserController@getUsers');
Route::post('videos', 'VideoController@getVideos');
Route::post('user/{id}/videos', 'VideoController@getUserVideos', function ($id) {
})->where('id', '[0-9]+');
Route::post('video/{id}', 'VideoController@videoFormat', function ($id) {
})->where('id', '[0-9]+');
Route::put('video/{id}', 'VideoController@videoUpdate', function ($id) {
})->where('id', '[0-9]+');
Route::post('video/{id}/comments', 'CommentController@getComment', function ($id) {
})->where('id', '[0-9]+');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('currentUser', 'UserController@getAuthenticatedUser');
    Route::delete('user/{id}', 'UserController@delete', function ($id) {
    })->where('id', '[0-9]+');
    Route::put('user/{id}', 'UserController@update', function ($id) {
    })->where('id', '[0-9]+');
    Route::get('user/{id}', 'UserController@getUser', function ($id) {
    })->where('id', '[0-9]+');
    Route::post('user/{id}/videoUpload', 'VideoController@uploadVideo', function ($id) {
    })->where('id', '[0-9]+');
    Route::delete('video/{id}', 'VideoController@videoDelete', function ($id) {
    })->where('id', '[0-9]+');
    Route::post('video/{id}/comment', 'CommentController@postComment', function ($id) {
    })->where('id', '[0-9]+');
});
