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

// Index page //
Route::get('/', 'IndexController@Index');

// Authentication endpoints //
Route::group(['prefix' => 'auth/'], function () {
    Route::get("login", "AuthController@index");
    Route::post("login", "AuthController@login");
    Route::post("register", "AuthController@registerUser");
    Route::get("register", "AuthController@register");
    Route::get("logout", "AuthController@Lmicrosoftogout");
});

// User endpoints //
Route::group(['prefix' => 'user/'], function () {
    Route::post("addOnlineApplication", "UserController@addOnlineApplicationToUser");
});


// Online applications endpoints //
Route::group(['prefix' => 'application/'], function () {
    Route::get('', 'OnlineApplicationController@Index');
    Route::get("getall", "OnlineApplicationController@getAll");
    Route::get("oauth", "OnlineApplicationController@OauthLogin");
    Route::get("go/{sourceCompany}/{application}", "OnlineApplicationController@goToOnlineApplication");
    Route::get('login/{sourceCompany}', 'OnlineApplicationController@doOnlineApplicationLogin');
    Route::get('getCookieValue', 'OnlineApplicationController@getUnencryptedOAuthToken');
    Route::get('command/{commandToExecute}', 'OnlineApplicationController@executeApplicationCommand');
});



Route::get('drive/{type}', 'OnlineApplicationController@index');



Route::get("/code/{sourceCompany}", "OnlineApplicationController@getTokenFromCodeForApplication");
