<?php

use App\Http\Controllers\UsuarioController;
use App\Mercado;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('mercados/{id}/foto', 'MercadoController@getImageProfile');
Route::get('usuarios/{id}/foto', 'UsuarioController@getImageProfile');
Route::get('productos/{id}/foto', 'ProductoController@getImageProfile');

Route::get('mercados/nombres', function(){
	return Mercado::get(['id', 'nombre']);
}); 

Route::post('auth', 'AuthController@authenticate');
Route::post('usuarios', 'UsuarioController@store');

Route::group(['middleware' => 'authorized'], function () {
	Route::get('/usuarios', 'UsuarioController@index');
	Route::get('/usuarios/{usuario}', 'UsuarioController@show');

	Route::post('usuarios/{id}/foto', 'UsuarioController@uploadImageProfile');
	Route::post('auth/gc_token', 'AuthController@saveGcToken');
	Route::put('auth/gc_token', 'AuthController@saveGcToken');
		
	Route::resource('mercados', 'MercadoController');
	Route::post('mercados/{id}/foto', 'MercadoController@uploadImageProfile');

	Route::resource('puestos', 'PuestoController');
	Route::resource('pedidos', 'PedidoController');
	Route::put('/pedidos/{id}/estado/{estado}', 'PedidoController@actualizarEstado');
	
	Route::get('productos/categorias', 'ProductoController@indexCategorias');
	Route::resource('productos', 'ProductoController');
	Route::post('productos/{id}/foto', 'ProductoController@uploadImageProfile');

	Route::put('transportista/asignar', 'TransportistaController@asignarAPI');

	// -------------------------------------------------------------------
	Route::post('tiendas', 'TiendaController@store');
	Route::get('tiendas', 'TiendaController@index');
	Route::get('tiendas/categorias', 'TiendaController@getCategorias');
	Route::get('tiendas/{id}', 'TiendaController@show');
	Route::get('tiendas/{id}/promociones', 'TiendaController@getPromociones');
	Route::post('tiendas/{id}/promociones', 'TiendaController@savePromociones');

	Route::patch('usuarios/{id}','UsuarioController@updateUser');
	//  -------------------------------------------
	//  fz
	//  --------------------------------------------

	Route::get('mercado-detalle/{id}','MercadoController@mercadoDetail');
	Route::get('puesto-detalle/{id}','PuestoController@puestoDetalle');

	//  -------------------------------------------
	//  endfz
	//  --------------------------------------------
});
