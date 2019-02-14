<?php
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


/*
|--------------------------------------------------------------------------
| API Mobile Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'v1.0'], function () {
    /**
     * this Block Route For Testing without middleware
     */
    Route::get('all-interest', 'Mobile\InterestController@index');
    Route::get('all-user', 'Mobile\UserController@getAllUser');
    Route::get('all-activity', 'Mobile\ActivityController@index');
    Route::get('all-community', 'Mobile\CommunityController@index');
    Route::get('all-event', 'Mobile\EventController@index');
//    Route::get('check-formula', 'Mobile\NearbyLocationController@checkSQL');
    Route::get('check-nearby', 'Mobile\NearbyLocationController@checkNearby');

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'Mobile\AuthController@login');
        Route::post('signup', 'Mobile\AuthController@signup');
        Route::get('signup/activate/{token}', 'Mobile\AuthController@signUpActivate');
        Route::post('check-email', 'Mobile\AuthController@checkEmailRegister');
        Route::post('check-username', 'Mobile\AuthController@checkUserNameRegister');
        Route::post('check-fullname', 'Mobile\AuthController@checkFullNameRegister');
        Route::post('check-phone', 'Mobile\AuthController@checkPhoneNumberRegister');

    });
    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::group(['prefix' => 'user'], function () {
            Route::get('index', 'Mobile\UserController@getAllUser');
            Route::post('logout', 'Mobile\AuthController@logout');
            Route::get('profile', 'Mobile\UserController@getAuthenticatedUser');
            Route::post('find-user-by-id', 'Mobile\UserController@getUserProfilePublic');
            Route::put('update-profile', 'Mobile\UserController@updateProfile');

        });
        Route::group(['prefix' => 'activity'], function () {
            Route::get('index', 'Mobile\ActivityController@index');
            Route::post('create', 'Mobile\ActivityController@store');
            Route::put('update', 'Mobile\ActivityController@update');
            Route::get('all-activity-by-user', 'Mobile\ActivityController@getAllActivityByUser');
            Route::post('find-activity-by-id', 'Mobile\ActivityController@getActivityPublic');
        });
        Route::group(['prefix' => 'community'], function () {
            Route::get('index', 'Mobile\CommunityController@index');
            Route::get('all-community-by-user', 'Mobile\CommunityController@getAllCommunityByUser');
            Route::post('create', 'Mobile\CommunityController@store');
            Route::put('update', 'Mobile\CommunityController@update');
            Route::post('find-community-by-id', 'Mobile\CommunityController@getCommunityPublic');

            Route::group(['prefix' => 'post'], function () {
                Route::post('create-post', 'Mobile\PostController@store');
                Route::get('find-post-by-id', 'Mobile\PostController@getPostPublic');
                Route::post('create-comment', 'Mobile\CommentController@store');
            });
        });
        Route::group(['prefix' => 'event'], function () {
            Route::get('index', 'Mobile\EventController@index');
            Route::post('create', 'Mobile\EventController@store');
            Route::put('update', 'Mobile\EventController@update');
            Route::get('all-event-by-user', 'Mobile\EventController@getAllEventByUser');
            Route::post('find-event-by-id', 'Mobile\EventController@getEventPublic');
        });


        //store interest on each modul
        Route::group(['prefix' => 'interest'], function () {
            Route::get('index', 'Mobile\InterestController@index');
            Route::post('create', 'Mobile\InterestController@store');

            //store user interest
            Route::post('user', 'Mobile\InterestController@createUserInterest');
            Route::post('user/{id}', 'Mobile\InterestController@update');

            //store Activity Interest
            Route::post('activity', 'Mobile\InterestController@createActivityInterest');
            Route::post('activity/{id}', 'Mobile\InterestController@updateActivityInterest');

            //store Community Interest
            Route::post('community', 'Mobile\InterestController@createCommunityInterest');
            Route::post('community/{id}', 'Mobile\InterestController@updateCommunityInterest');

            //store Event Interest
            Route::post('event', 'Mobile\InterestController@createEventInterest');
            Route::post('event/{id}', 'Mobile\InterestController@updateEventInterest');

        });

        Route::group(['prefix' => 'nearby-location'], function () {
            Route::get('', 'Mobile\NearbyLocationController@getLocationUser');
            Route::post('submit', 'Mobile\NearbyLocationController@submitLocationUser');
        });

    });
});

/*
|--------------------------------------------------------------------------
| API Backend Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'v2.0'], function () {
    /**
     * this Block Route For Testing without middleware
     */
    Route::get('all-interest', 'Mobile\InterestController@index');
    Route::get('all-user', 'Mobile\UserController@getAllUser');
    Route::get('all-activity', 'Mobile\ActivityController@index');
    Route::get('all-community', 'Mobile\CommunityController@index');
    Route::get('all-event', 'Mobile\EventController@index');

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'Mobile\AuthController@login');
        Route::post('signup', 'Mobile\AuthController@signup');
        Route::get('signup/activate/{token}', 'Mobile\AuthController@signUpActivate');

    });
    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::group(['prefix' => 'user'], function () {
            Route::get('index', 'Mobile\UserController@getAllUser');
            Route::get('logout', 'Mobile\AuthController@logout');
            Route::get('profile', 'Mobile\UserController@getAuthenticatedUser');
            Route::post('account/{id}', 'Mobile\UserController@getUserProfilePublic');
            Route::post('update-profile', 'Mobile\UserController@updateProfile');

        });
        Route::group(['prefix' => 'activity'], function () {
            Route::get('index', 'Mobile\ActivityController@index');
            Route::get('{id}', 'Mobile\ActivityController@getActivityPublic');
            Route::post('create', 'Mobile\ActivityController@store');
            Route::post('update/{id}', 'Mobile\ActivityController@update');
        });
        Route::group(['prefix' => 'community'], function () {
            Route::get('index', 'Mobile\CommunityController@index');
            Route::get('{id}', 'Mobile\CommunityController@getCommunityPublic');
            Route::post('create', 'Mobile\CommunityController@store');
            Route::post('update/{id}', 'Mobile\CommunityController@update');

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
            Route::post('update/{id}', 'Mobile\EventController@update');
        });


        //store interest on each modul
        Route::group(['prefix' => 'interest'], function () {
            Route::get('index', 'Mobile\InterestController@index');
            Route::post('create', 'Mobile\InterestController@store');

            //store user interest
            Route::post('user', 'Mobile\InterestController@createUserInterest');
            Route::post('user/{id}', 'Mobile\InterestController@update');

            //store Activity Interest
            Route::post('activity', 'Mobile\InterestController@createActivityInterest');
            Route::post('activity/{id}', 'Mobile\InterestController@updateActivityInterest');

            //store Community Interest
            Route::post('community', 'Mobile\InterestController@createCommunityInterest');
            Route::post('community/{id}', 'Mobile\InterestController@updateCommunityInterest');

            //store Event Interest
            Route::post('event', 'Mobile\InterestController@createEventInterest');
            Route::post('event/{id}', 'Mobile\InterestController@updateEventInterest');

        });
    });
});
