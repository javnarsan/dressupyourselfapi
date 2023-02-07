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
    Route::get('/users', 'API\RegisterController@getUsers');
    //Articulo
    Route::get('articulos', 'API\ArticuloController@index');
    Route::get('articulos/genero/{genero}', 'API\ArticuloController@getByGenero');
    Route::get('articulos/marca/{marca}', 'API\ArticuloController@getByMarca');
    Route::patch('articulos/vistas/{id}', 'API\ArticuloController@updateVistas');
    Route::post('articulos', 'API\ArticuloController@store');
    Route::get('articulos/{id}', 'API\ArticuloController@show');
    Route::patch('articulos/{articulo}', 'API\ArticuloController@update');
    Route::delete('articulos/{articulo}', 'API\ArticuloController@destroy');
    //Compra
    Route::post('/compras', 'API\CompraController@store');
    Route::get('/carrito/{id}', 'API\CompraController@getCarrito');
    Route::patch('compras/confirmar/{id}', 'API\CompraController@confirmarCompra');
    Route::get('compras/{id}', 'API\CompraController@show');
    Route::get('compras', 'API\CompraController@index');



});
