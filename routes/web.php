<?php

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

Route::get('/', 'HomeController@index');
Route::get('/login', 'AuthController@index');
Route::post('/auth-admin', 'AuthController@authenticateAdmin')->name('login-admin');

Route::get('/politicas', function(){
	return view('home.politicas');
});

Route::group(['middleware' => 'administrator'], function () {
	Route::get('/logout', 'AuthController@logout');
	Route::post('/transportista/asignar', 'TransportistaController@asignarWB')->name('asignar-transportista');

	Route::get('/mercados', 'MercadoController@indexPage');
	Route::get('/mercados/create', 'MercadoController@createMercado');
	Route::post('/mercados/store', 'MercadoController@storeMercado');
	Route::post('/mercados/updateMercado', 'MercadoController@updateMercado');
	Route::get('/mercados/{id}', 'MercadoController@showMercado');
	Route::get('/mercados/{id}/edit', 'MercadoController@editMercado');
	Route::get('/mercados/{id}/delete', 'MercadoController@deleteMercado');

	Route::get('/usuarios', 'UsuarioController@indexPage');
	Route::post('/usuarios', 'UsuarioController@storeUsuario');
	Route::get('/usuarios/create', 'UsuarioController@createUser');
	Route::post('/usuarios/updateUsuario', 'UsuarioController@updateUsuario');
	Route::get('/usuarios/{id}', 'UsuarioController@showUsuario');
	Route::get('/usuarios/{id}/edit', 'UsuarioController@editUsuario');
	Route::get('/usuarios/{id}/delete', 'UsuarioController@deleteUsuario');

	Route::get('/puestos', 'PuestoController@indexPage');
	Route::get('/puestos/{id}', 'PuestoController@showPuesto');

	Route::get('/productos', 'ProductoController@indexPage');
	Route::get('/productos/{id}', 'ProductoController@showProducto');

	Route::get('negocios', 'TiendaController@indexPage');
	Route::get('negocios/{id}', 'TiendaController@showNegocio');
	Route::get('negocios/{id}/creditos', 'TiendaController@showCreditosPage');
	Route::post('negocios/agregar-creditos', 'TiendaController@addCreditos');
});
