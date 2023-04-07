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
    Route::get('/user/{id}', 'API\RegisterController@show');
    Route::put('/user/{id}', 'API\RegisterController@update');
    
    //Articulo
    Route::get('/articulos', 'API\ArticuloController@index');
    Route::get('/catalogo', 'API\ArticuloController@showCatalogo');
    Route::get('/catalogo/destacados', 'API\ArticuloController@showCatalogoDestacados');
    Route::get('/articulo/{id}/tallas', 'API\ArticuloController@showTallas');
    Route::post('/articulo', 'API\ArticuloController@store');
    Route::get('/articulo/{id}', 'API\ArticuloController@show');
    Route::get('/articulos/genero/{genero}', 'API\ArticuloController@getByGenero');
    Route::get('/articulos/marca/{marca}', 'API\ArticuloController@getByMarca');
    Route::get('/articulos/edad/{edad}', 'API\ArticuloController@getByEdad');
    Route::patch('/articulo/vistas/{id}', 'API\ArticuloController@updateVistas');
    Route::put('/articulo/{id}', 'API\ArticuloController@update');
    //Compra
    Route::get('/compras', 'API\CompraController@index');
    Route::get('/compra/{id}', 'API\CompraController@show');
    Route::post('/compra', 'API\CompraController@store');
    Route::get('/carrito/{id}', 'API\CompraController@getCarrito');
    Route::patch('/compras/confirmar/{id}', 'API\CompraController@confirmarCompra');
    Route::get('/compras/{id}/articulos', 'API\CompraController@articulosCompradosUser');
    //Valoraciones
    Route::post('/valoracion', 'ValoracionController@store');
    Route::delete('/valoracion/{id}', 'ValoracionController@destroy');

});
