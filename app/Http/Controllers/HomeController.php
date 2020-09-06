<?php

namespace App\Http\Controllers;

use App\Pedido;
use App\Usuario;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	public function index(Request $request)
	{
		if (!session('isAuthenticated', false)) {
			return redirect('/login');
		}

		$type = $request->query("type", "pendientes");

		switch ($type) {
			case 'all':
				$pedidos = Pedido::with(
					array(
						'mercado' => function ($query) {
							$query->select('id', 'nombre');
						},
						'cliente' => function ($query) {
							$query->select('id', 'nombres', 'apellidos');
						},
						'entrega' => function ($query) {
							$query->select('id', 'id_pedido', 'direccion_entrega');
						}
					)
				)->orderby('fecha_registro', 'desc')->paginate(5);
				break;
			case 'pendientes':
				$pedidos = Pedido::with(
					array(
						'mercado' => function ($query) {
							$query->select('id', 'nombre');
						},
						'cliente' => function ($query) {
							$query->select('id', 'nombres', 'apellidos');
						},
						'entrega' => function ($query) {
							$query->select('id', 'id_pedido', 'direccion_entrega', 'estado');
						}
					)
				)->whereHas('entrega', function ($query) {
					$query->where('estado', '=', 'WAITING');
				})->orderby('fecha_registro', 'asc')->paginate(5);
				break;
			case 'progreso':
				$pedidos = Pedido::with(
					array(
						'mercado' => function ($query) {
							$query->select('id', 'nombre');
						},
						'cliente' => function ($query) {
							$query->select('id', 'nombres', 'apellidos');
						},
						'entrega' => function ($query) {
							$query->select('id', 'id_pedido', 'direccion_entrega', 'estado');
						}
					)
				)->whereHas('entrega', function ($query) {
					$query->where('estado', '=', 'IN_PROGRESS');
				})->orderby('fecha_registro', 'asc')->paginate(5);
				break;
			case 'finalizado':
				$pedidos = Pedido::with(
					array(
						'mercado' => function ($query) {
							$query->select('id', 'nombre');
						},
						'cliente' => function ($query) {
							$query->select('id', 'nombres', 'apellidos');
						},
						'entrega' => function ($query) {
							$query->select('id', 'id_pedido', 'direccion_entrega', 'estado');
						}
					)
				)->whereHas('entrega', function ($query) {
					$query->where('estado', '=', 'ENTREGADA');
				})->orderby('fecha_registro', 'desc')->paginate(5);
				break;
		}
		return view('home.index', compact('pedidos', 'type'));
	}
}
