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
Route::post('/register', 'Auth\RegisterController@register');

Route::post('password/email', 
    'Auth\ForgotPasswordController@getResetToken');

Route::group(['prefix' => 'jobs'], function ($app) {
    Route::get('/','JobsController@index');
    Route::get('{id}', 'JobsController@find');
});

Route::get('jobs/sort-by/{name}', 'JobsController@sort');

Route::group(['middleware' => ['auth:api'], 'prefix' => 'jobs'], function ($app) {
    Route::post('/','JobsController@store');
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



