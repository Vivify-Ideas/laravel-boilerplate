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

Route::group([ 'namespace' => 'Api' ], function() {
  Route::group([ 'middleware' => [ 'oauthClient']], function() {
    Route::post('/auth/login', 'Auth\OauthController@login');
    Route::post('/auth/refresh-token', 'Auth\OauthController@refresh');
    Route::post('/users/create', 'User\UsersController@create');
  });
  
  Route::group([ 'middleware' => [ 'auth:api' ]], function() {
    Route::get('/users/show', 'User\UsersController@show');
  });
});