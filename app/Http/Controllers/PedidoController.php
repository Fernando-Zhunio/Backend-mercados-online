<?php

namespace App\Http\Controllers;

use App\DetallePedido;
use App\Pedido;
use App\Producto;
use App\Fault;
use App\Puesto;
use App\Tienda;
use App\UbicacionPedido;
use App\Usuario;
use App\Utils\Notificaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PedidoController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$tipo = $request->query("type", "ALL");
		$id = $request->query("id", null);

		switch ($tipo) {
			case 'ALL':
				$datos = Pedido::all()->each(function ($item) {
					if ($item->tipo == "MERCADO") $item->append('nombre_mercado');
					else if($item->tipo == "NEGOCIO") $item->append('nombre_negocio');
				});
				break;
			case 'CLIENTE':
				$datos = Pedido::where(['id_usuario' => $id])->get()->each(function ($item) {
					if ($item->tipo == "MERCADO") $item->append('nombre_mercado');
					else if($item->tipo == "NEGOCIO") $item->append('nombre_negocio');
				});
				break;
			case 'TRANSPORTISTA':
				$datos = Pedido::where(['id_transportista' => $id])->get()->each(function ($item) {
					if ($item->tipo == "MERCADO") $item->append('nombre_mercado');
					else if($item->tipo == "NEGOCIO") $item->append('nombre_negocio');
				});
				break;
			case 'VENDEDOR':
				$datos = DetallePedido::where('id_vendedor', $id)
					->groupBy(['id_venta', 'id_vendedor'])
					->selectRaw('id_venta, id_vendedor, sum(subtotal) as total')
					->get();

				$response = array();

				foreach ($datos as $detalle) {
					$pedido = Pedido::find($detalle['id_venta']);

					$inpDet['id'] = $pedido->id;
					$inpDet['id_usuario'] = $pedido->id_usuario;
					$inpDet['id_mercado'] = $pedido->id_establecimiento;
					$inpDet['id_transportista'] = $pedido->id_transportista;
					$inpDet['costo_venta'] = $detalle['total'];
					$inpDet['costo_envio'] = 0;
					$inpDet['total'] = $detalle['total'];
					$inpDet['tipo'] = "MERCADO";
					$inpDet['forma_pago'] = $pedido->forma_pago;
					$inpDet['fecha_registro'] = $pedido->fecha_registro;
					$inpDet['fecha_actualiza'] = $pedido->fecha_actualiza;
					$inpDet['estado'] = $pedido->estado;
					$inpDet['nombre_mercado'] = $pedido->nombre_mercado;

					array_push($response, $inpDet);
				}

				$datos = $response;
				break;

			case 'TIENDERO':
				$datos = DetallePedido::where('id_vendedor', $id)
					->groupBy(['id_venta', 'id_vendedor'])
					->selectRaw('id_venta, id_vendedor, sum(subtotal) as total')
					->get();

				$response = array();

				foreach ($datos as $detalle) {
					$pedido = Pedido::find($detalle['id_venta']);

					$inpDet['id'] = $pedido->id;
					$inpDet['id_usuario'] = $pedido->id_usuario;
					$inpDet['id_negocio'] = $pedido->id_establecimiento;
					$inpDet['id_transportista'] = null;
					$inpDet['costo_venta'] = $detalle['total'];
					$inpDet['costo_envio'] = 0;
					$inpDet['total'] = $detalle['total'];
					$inpDet['tipo'] = "NEGOCIO";
					$inpDet['forma_pago'] = $pedido->forma_pago;
					$inpDet['fecha_registro'] = $pedido->fecha_registro;
					$inpDet['fecha_actualiza'] = $pedido->fecha_actualiza;
					$inpDet['estado'] = $pedido->estado;
					$inpDet['nombre_negocio'] = $pedido->nombre_negocio;


					array_push($response, $inpDet);
				}

				$datos = $response;
				break;
			default:
				$datos = Pedido::all();
				break;
		}

		return response()->json($datos);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$input['id_usuario'] = $request->id_usuario;
		$input['costo_venta'] = $request->costo_venta;
		$input['tipo'] = $request->tipo;

		if ($request->tipo == "MERCADO") {
			$input['id_establecimiento'] = $request->id_mercado;
			$input['id_transportista'] = $request->id_transportista;
			$input['costo_envio'] = $request->costo_envio;
		}
		else{
			$input['id_establecimiento'] = $request->id_negocio;
			$input['costo_envio'] = $request->costo_envio;

		}

		$entrega['direccion_entrega'] = $request->direccion_entrega;
		$entrega['lat_entrega'] = $request->lat_entrega;
		$entrega['lng_entrega'] = $request->lng_entrega;
		$entrega['celular_contacto'] = $request->celular_contacto;
		$input['total'] = $request->total;

		DB::beginTransaction();
		$pedido = Pedido::create($input);
		$entrega['id_pedido'] = $pedido->id;

		foreach ($request->detalle as $detalle) {
			$producto = null;
			$producto = Producto::find($detalle['id_producto']);

			$inpDet['id_venta'] = $pedido->id;
			$inpDet['id_producto'] = $detalle['id_producto'];
			$inpDet['precio'] = $producto->precio;
			$inpDet['cantidad'] = $detalle['cantidad'];
			$inpDet['subtotal'] = $producto->precio * $detalle['cantidad'];
			$producto->stock = $producto->stock - $detalle['cantidad'];

			if ($request->tipo == "MERCADO") {
				$inpDet['id_vendedor'] = $detalle['id_vendedor'];
				$inpDet['id_puesto'] = Puesto::where(['id_vendedor' => $detalle['id_vendedor'], 'estado' => 1])->first('id')->id;
			} else {
				$inpDet['id_puesto'] = $request->id_tienda;
				$inpDet['id_vendedor'] = $request->id_vendedor;
			}

			$producto->save();
			DetallePedido::create($inpDet);
		}

		$entrega['estado'] = "IN_PROGRESS";
		UbicacionPedido::create($entrega);

		if ($request->tipo == "NEGOCIO") {
			$notificaciones = new Notificaciones();
			$cliente = Usuario::find($request->id_usuario);
			$vendedor = Usuario::find($request->id_vendedor);

			$titulo = "Nuevo pedido #" . $pedido->id;
			$mensaje = "Cliente: " . $cliente->nombres . " " . $cliente->apellidos;

			$notificaciones->send($vendedor->gc_token, $titulo, $mensaje);
		}

		if ($request->tipo == "NEGOCIO") {
			$negocio = Tienda::find($request->id_negocio);
			$negocio->creditos_totales = $negocio->creditos_totales - 0.25;
			if ($negocio->creditos_totales < 0.25) {
				$negocio->estado = 2;
			}

			$negocio->save();
		}

		DB::commit();

		$response['id_pedido'] = $pedido->id;
		$response['mensaje'] = "Pedido ingresado";

		return response()->json($response, Response::HTTP_CREATED);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Pedido  $ventas
	 * @return \Illuminate\Http\Response
	 */
	public function show(int $idPedido, Request $request)
	{
		$tipo = $request->query("type", "none");

		if ($tipo == "none") {
			return Pedido::find($idPedido);
		} else {
			return Pedido::with(array(
				'mercado' => function ($query) {
					$query->select('id', 'nombre');
				},
				'negocio' => function ($query) {
					$query->select('id', 'nombre');
				},
				'cliente' => function ($query) {
					$query->select('id', 'nombres', 'apellidos', 'celular');
				},
				'transportista' => function ($query) {
					$query->select('id', 'nombres', 'apellidos', 'celular');
				},
				'entrega',
				'detalles.puesto' => function ($query) {
					$query->select('id', 'codigo');
				},
				'detalles.vendedor' => function ($query) {
					$query->select('id', 'nombres', 'apellidos');
				}
			))->where('id', $idPedido)->first();
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Pedido  $ventas
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Pedido $pedido)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Pedido  $pedido
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Pedido $pedido)
	{
		//
	}

	public function actualizarEstado(int $id, string $estado)
	{
		
		$ubicacion = UbicacionPedido::where('id_pedido', $id)->first();
		$ubicacion->estado = $estado;
		$ubicacion->save();

		$response['mensaje'] = "Pedido actualizado correctamente";

		return response()->json($response, Response::HTTP_CREATED);
	}
}
