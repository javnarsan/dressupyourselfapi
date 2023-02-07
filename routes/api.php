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


Route::post('register', 'API\RegisterController@register');

Route::post('login', 'API\RegisterController@login');

Route::middleware('auth:api')->group( function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
	Route::resource('articulos', 'API\ArticuloController');
    Route::get('/articulos/genero/{genero}', 'API\ArticuloController@getByGenero');
    Route::get('/articulos/marca/{marca}', 'API\ArticuloController@getByMarca');
    Route::get('/users', 'API\RegisterController@getUsers');

});
