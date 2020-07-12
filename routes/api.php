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
    'middleware' => 'jwt.auth',
    'prefix' => 'recipe'
], function () {
    Route::get('', 'Recipe\RecipeController@index');
    Route::post('', 'Recipe\RecipeController@store');

    Route::get('/{id}', 'Recipe\RecipeController@show');
    Route::delete('/{id}', 'Recipe\RecipeController@delete');
    Route::put('/{id}', 'Recipe\RecipeController@update');
});


Route::group([
    'middleware' => 'jwt.auth',
    'prefix' => 'ingredient'
], function () {
    Route::get('', 'Ingredient\IngredientController@index');
    Route::post('', 'Ingredient\IngredientController@store');

    Route::get('/{id}', 'Ingredient\IngredientController@show');
    Route::delete('/{id}', 'Ingredient\IngredientController@delete');
    Route::put('/{id}', 'Ingredient\IngredientController@update');
});
