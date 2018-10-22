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

Route::group(['prefix' => 'v1.0'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'Mobile\AuthController@login');
        Route::post('signup', 'Mobile\AuthController@signup');
        Route::post('check-email', 'Mobile\AuthController@checkEmail');
        Route::post('check-username', 'Mobile\AuthController@checkUsername');
    });
    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::group(['prefix' => 'user'], function () {
            Route::get('logout', 'Mobile\AuthController@logout');
            Route::get('profile', 'Mobile\UserController@getAuthenticatedUser');
            Route::post('user/{id}', 'Mobile\UserController@getUserProfilePublic');
            Route::post('update-profile', 'Mobile\UserController@updateProfile');
        });
        Route::group(['prefix' => 'activity'], function () {
            Route::post('create', 'Mobile\ActivityController@create');
            Route::post('update', 'Mobile\ActivityController@update');
        });
        Route::group(['prefix' => 'community'], function () {

            Route::post('create', 'Mobile\CommunityController@create');
            Route::post('update', 'Mobile\CommunityController@update');

            Route::group(['prefix' => 'post'], function () {
                Route::post('create-post', 'Mobile\PostController@create');
                Route::post('create-reply', 'Mobile\PostController@createReply');
                Route::post('create-reply-by-reply', 'Mobile\PostController@createReplyByReply');
            });
        });
        Route::group(['prefix' => 'event'], function () {
            Route::post('create', 'Mobile\EventController@create');
            Route::post('update', 'Mobile\EventController@update');
        });
        Route::group(['prefix' => 'timeline'], function () {
            Route::post('index', 'Mobile\TimeLineController@create');
        });
    });
});

