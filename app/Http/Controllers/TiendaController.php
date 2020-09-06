<?php

namespace App\Http\Controllers;

use App\CategoriaNegocio;
use App\Mercado;
use App\NegociosSubcategorias;
use App\Parametro;
use App\Producto;
use App\ProductoNegocio;
use App\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TiendaController extends Controller
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
		$categoria = $request->query("categoria");

		switch ($tipo) {
			case 'ALL':
				$tiendas = Tienda::where([
					['estado', '=', 1],
					['tipo_negocio', '=', $categoria]
				])->get();

				$maxDistanceKm = (int) Parametro::where('parametro', 'MAX_DISTANCE_KM')->first('valor')->valor;
				$punto = array('lat' => $request->query("lat"), 'long' => $request->query("lng"));

				$tiendas = $tiendas->filter(function ($tienda) use ($punto, $maxDistanceKm) {
					$distance = $this->getDistanceBetweenPoints($punto['lat'], $punto['long'], $tienda->latitud, $tienda->longitud);
					return $distance < $maxDistanceKm;
				})->values();

				break;
			case 'TIENDERO':
				$tiendas = Tienda::where('id_usuario', $id)->whereIn('estado', [1, 2])->get();
				break;
		}

		return $tiendas;
	}

	public function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2)
	{
		$theta = $lon1 - $lon2;
		$miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
		$miles = acos($miles);
		$miles = rad2deg($miles);
		$miles = $miles * 60 * 1.1515;
		$kilometers = $miles * 1.609344;
		return $kilometers;
	}


	public function indexPage()
	{
		$negocios = Tienda::with([
			'usuario' => function ($query) {
				$query->select('id', 'nombres', 'apellidos');
			}
		])->paginate(10);

		return view('tiendas.index', compact('negocios'));
	}

	public function getCategorias()
	{
		return CategoriaNegocio::where('estado', 1)->get(['id', 'nombre']);
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
		$datos = json_decode($request->payload);
		$input['nombre'] = $datos->nombre;
		$input['id_usuario'] = $datos->id_usuario;
		$input['descripcion'] = $datos->descripcion;
		$input['direccion'] = $datos->direccion;
		$input['longitud'] = $datos->longitud;
		$input['latitud'] = $datos->latitud;
		$input['telefono'] = $datos->telefono;
		$input['tipo_negocio'] = $datos->tipo_negocio;

		if (isset($datos->ciudad)) {
			$input['ciudad'] = $datos->ciudad;
		}

		DB::beginTransaction();

		$negocio = Tienda::create($input);
		
		$promo['nombre'] = "Todos";
		$promo['orden'] = 1;
		$promo['id_negocio'] = $negocio->id;

		NegociosSubcategorias::create($promo);

		$path = $request->foto->storeAs('public/images', 'neg-' . $negocio->id . '.jpg');
		$path = str_replace("public/", "", $path);
		$negocio->url_imagen = $path;
		$negocio->save();

		$response['id'] = $negocio->id;
		$response['mensaje']  = "Negocio registrado con exito";

		DB::commit();

		return response()->json($response, Response::HTTP_CREATED);
	}

	public function addCreditos(Request $request)
	{
		$creditos = (float) trim($request->creditos);
		$negocio = Tienda::find($request->id);

		$negocio->creditos_totales = $negocio->creditos_totales + $creditos;
		$negocio->estado = 1;
		$negocio->save();

		return redirect('/negocios')->with('mensaje', 'Creditos abonados correctamente');
	}

	public function showCreditosPage(int $idNegocio)
	{
		$negocio = Tienda::find($idNegocio);
		return view('tiendas.creditos', compact('negocio'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Tienda  $tienda
	 * @return \Illuminate\Http\Response
	 */
	public function show(int $id)
	{
		return Tienda::find($id);
	}

	public function showNegocio(int $idNegocio, Request $request)
	{
		$negocio = Tienda::with([
			'usuario' => function ($query) {
				$query->select('id', 'nombres', 'apellidos');
			},
			'categoria' => function ($query) {
				$query->select('id', 'nombre');
			}
		])->find($idNegocio);

		$listaProductos = ProductoNegocio::where('id_negocio', $negocio->id)->get(['id_producto']);
		$productos = Producto::whereIn("id", $listaProductos)->paginate(10);

		return view('tiendas.show', compact('negocio', 'productos'));
	}

	public function getPromociones(int $id){
		$promociones = NegociosSubcategorias::where('id_negocio', $id)->orderBy('orden')->get();
		return $promociones;
	}

	public function savePromociones(int $id, Request $request){
		$orden = DB::selectOne("SELECT MAX(a.orden) orden FROM negocios_subcategorias a WHERE a.id_negocio = $id")->orden;
		$promo['nombre'] = $request->nombre;
		$promo['id_negocio'] = $id;
		$promo['orden'] = $orden + 1;

		$promocion = NegociosSubcategorias::create($promo);
		$response['id'] = $promocion->id;
		$response['mensaje'] = "Promocion creada correctamente";

		return $response;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Tienda  $tienda
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Tienda $tienda)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Tienda  $tienda
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Tienda $tienda)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Tienda  $tienda
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Tienda $tienda)
	{
		//
	}
}
