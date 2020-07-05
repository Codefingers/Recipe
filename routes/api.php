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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('register', 'JWTAuthController@register');
    Route::post('login', 'JWTAuthController@login');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'jwt.auth'
], function () {
    Route::get('/recipe', 'Recipe\RecipeController@index');
    Route::post('/recipe', 'Recipe\RecipeController@store');

    Route::get('/recipe/{id}', 'Recipe\RecipeController@show');
    Route::delete('/recipe/{id}', 'Recipe\RecipeController@delete');
    Route::put('/recipe/{id}', 'Recipe\RecipeController@update');
});
