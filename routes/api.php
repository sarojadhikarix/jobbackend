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

//Authentication
Route::group(['prefix' => 'register'], function ($app) {
    Route::post('/', 'Auth\RegisterController@register');
    Route::get('{id}', 'Auth\RegisterController@find');
});

Route::group(['middleware' => ['auth:api'], 'prefix' => 'user'], function ($app) {
    Route::get('{id}', 'UserController@find');
});

Route::post('password/email', 
    'Auth\ForgotPasswordController@getResetToken');

Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::group(['prefix' => 'jobs'], function ($app) {
    Route::get('/','JobsController@index');
    Route::get('{id}', 'JobsController@find');
    Route::get('user/{id}', 'JobsController@findByUser');
});

Route::get('jobs/sort-by/{name}', 'JobsController@sort');

Route::group(['middleware' => ['auth:api'], 'prefix' => 'jobs'], function ($app) {
    Route::post('/','JobsController@store');
    Route::post('update','JobsController@update');
    Route::post('addstatus','JobsController@addJobStatus');
    Route::post('updatestatus','JobsController@updateJobStatus');
    Route::post('getstatus','JobsController@findJobStatus');
    Route::get('all','JobsController@allJobs');

});

Route::group(['prefix' => 'categories'], function ($app) {
    Route::get('/','CategoryController@index');
    Route::get('{id}', 'CategoryController@find');
});

Route::group(['middleware' => ['auth:api'], 'prefix' => 'categories'], function ($app) {
    Route::post('/','CategoryController@store');
});

Route::post('search/', 'JobsController@search');

Route::post('sendmail/', 'MailController@send');

Route::group(['middleware' => ['auth:api'], 'prefix' => 'cv'], function ($app) {
    Route::get('{id}', 'CVController@find');
    Route::post('addfile', 'CVController@storefile');
    Route::delete('file/{id}', 'CVController@deletefile');
    Route::post('add', 'CVController@add');
    Route::delete('{id}', 'CVController@delete');
    Route::post('update','CVController@update');
    Route::post('search/', 'CVController@search');
});

Route::group(['middleware' => ['auth:api'], 'prefix' => 'propic'], function ($app) {
    Route::post('add', 'ImageuploadController@store');
    Route::post('update','ImageuploadController@update');
    Route::delete('{id}', 'ImageuploadController@delete');

});


