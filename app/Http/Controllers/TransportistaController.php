<?php

namespace App\Http\Controllers;

use App\DetallePedido;
use App\Pedido;
use App\UbicacionPedido;
use App\Usuario;
use App\Utils\Notificaciones;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class TransportistaController extends Controller
{
	public function asignarAPI(Request $request)
	{
		$this->asignar($request);
		$response['mensaje'] = "Transportista asignado correctamente";
		return response()->json($response, Response::HTTP_CREATED);
	}

	public function asignarWB(Request $request)
	{
		$this->asignar($request);
		return redirect()->back()->with('mensaje', 'Transportista asignado correctamente');
	}

	private function asignar(Request $request)
	{
		$notificaciones = new Notificaciones();
		$pedido = Pedido::find($request->id_pedido);
		$usuario = Usuario::find($pedido->id_usuario);
		$transportista = Usuario::find($request->id_transportista);

		$titulo = "Su pedido #" . $pedido->id . " ha sido asignado";
		$mensaje = "Transportista: " . $transportista->nombres . " " . $transportista->apellidos;

		$notificaciones->send($usuario->gc_token, $titulo, $mensaje);

		$detalles = DetallePedido::where('id_venta', $pedido->id)->get(['id', 'id_vendedor']);
		
		$vendedoresNotificados = array();

		foreach ($detalles as $detalle) {
			$vendedor = Usuario::find($detalle->id_vendedor);
			
			if(!in_array($vendedor->id, $vendedoresNotificados)){
				array_push($vendedoresNotificados, $vendedor->id);
				$notificaciones->send($vendedor->gc_token, $titulo , $mensaje);
			}
		}

		$pedido = Pedido::find($request->id_pedido);
		$transportista = Usuario::find($request->id_transportista);

		$pedido->id_transportista = $transportista->id;
		$ubicacion = UbicacionPedido::where('id_pedido', $pedido->id)->first();

		$ubicacion->estado = "IN_PROGRESS";

		$pedido->save();
		$ubicacion->save();
	}
}
