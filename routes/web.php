<?php

use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;

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

// Authentication endpoints //
Route::get("login", "AuthController@index");

Route::post("login", "AuthController@login");

Route::post("register", "AuthController@createAccount");

Route::get("register", "AuthController@register");


// Online applications endpoints //
Route::get('/', 'OnlineApplicationController@Index');

Route::get('drive/{type}', 'OnlineApplicationController@index');

Route::get("getall", "OnlineApplicationController@getAll");

Route::post("addOnlineApplication", "OnlineApplicationController@addOnlineApplication");

Route::get("oauth", "OnlineApplicationController@OauthLogin");

Route::get("getOauth", "OnlineApplicationController@OAuthLogin");

Route::get("/code/{applicationType}", "OnlineApplicationController@getToken");

