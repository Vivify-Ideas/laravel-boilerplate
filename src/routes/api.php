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
Route::middleware('oauthClient')->post('/auth/login', 'Api\Auth\OauthController@login');
Route::middleware('oauthClient')->post('/auth/refresh-token', 'Api\Auth\OauthController@refresh');

Route::middleware('auth:api')->get('/users/show', 'Api\User\UsersController@show');
Route::post('/check-email', 'Api\User\UsersController@checkEmail');
Route::middleware('oauthClient')->post('/users/create', 'Api\User\UsersController@create');

