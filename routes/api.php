<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Auth'], function() {
    //Start Auth Routes
    Route::post('register','AuthController@register');
    Route::post('login','AuthController@login');
    Route::get('/email/Verify', 'VerificationController@Verify');
    //End Auth Routes

});


Route::group([ 'middleware' => ['verified','auth:api']], function() {
    Route::post('show/{id}', 'ProfileController@show');

    Route::post('article', 'ArticleController@store');
    Route::post('article/{id}', 'ArticleController@update');
    Route::delete('article/{id}', 'ArticleController@destroy');
});
