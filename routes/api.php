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

Route::group(['prefix' => 'v1.0'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'Mobile\AuthController@login');
        Route::post('signup', 'Mobile\AuthController@signup');
        Route::post('check-email', 'Mobile\AuthController@checkEmailRegister');
        Route::post('check-username', 'Mobile\AuthController@checkUserNameRegister');
    });
    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::group(['prefix' => 'user'], function () {
            Route::get('logout', 'Mobile\AuthController@logout');
            Route::get('profile', 'Mobile\UserController@getAuthenticatedUser');
            Route::post('account/{id}', 'Mobile\UserController@getUserProfilePublic');
            Route::post('update-profile', 'Mobile\UserController@updateProfile');
        });
        Route::group(['prefix' => 'activity'], function () {
            Route::get('index', 'Mobile\ActivityController@index');
            Route::get('{id}', 'Mobile\ActivityController@getActivityPublic');
            Route::post('create', 'Mobile\ActivityController@store');
            Route::post('update', 'Mobile\ActivityController@update');
        });
        Route::group(['prefix' => 'community'], function () {
            Route::get('index', 'Mobile\CommunityController@index');
            Route::get('{id}', 'Mobile\CommunityController@getCommunityPublic');
            Route::post('create', 'Mobile\CommunityController@store');
            Route::post('update', 'Mobile\CommunityController@update');

            Route::group(['prefix' => 'post'], function () {
                Route::post('create-post', 'Mobile\PostController@store');
                Route::get('{id}', 'Mobile\PostController@getPostPublic');
                Route::post('create-comment', 'Mobile\CommentController@store');
            });
        });
        Route::group(['prefix' => 'event'], function () {
            Route::get('index', 'Mobile\EventController@index');
            Route::get('{id}', 'Mobile\EventController@getEventPublic');
            Route::post('create', 'Mobile\EventController@store');
            Route::post('update', 'Mobile\EventController@update');
        });
    });
});

