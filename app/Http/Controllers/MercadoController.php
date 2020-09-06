<?php

namespace App\Http\Controllers;

use App\Mercado;
use App\Fault;
use App\Puesto;
use App\Utils\Constantes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MercadoController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		if($request->query("childs", "no") == "no"){
			return Mercado::where('estado', 1)->get();
		}
		else{
			return Mercado::with('puestos')->where('estado', 1)->get();
		}
	}

	public function mercadoDetail($id){
		$mercado = Mercado::with('puestos.vendedor:id,nombres,apellidos,imagen_perfil')->find($id);
		// $mercado = Mercado::with('puestos.vendedor')->find($id);
		// $mercado->puestos()->vendedor;
		return response()->json(['success'=>true,'data'=>$mercado]);
	}

	

	public function indexPage(){
		$mercados = Mercado::where('estado', 1)->paginate(5);

		$mercados->each(function ($items){
			$items->append('cantidad_puestos');
		});

		return view('mercados.index', compact('mercados'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		
	}

	public function createMercado(){
		return view('mercados.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		
	}

	public function storeMercado(Request $request){
		$input['nombre'] = $request->nombre;
		$input['direccion'] = $request->direccion;
		$input['descripcion'] = $request->descripcion;

		$maxID = Mercado::max('id');

		$input['codigo_mercado'] = 'MER-' . ($maxID + 1);

		DB::beginTransaction();

		$mercado = Mercado::create($input);
		$path = $request->foto->storeAs('public/images', 'mer-' . $mercado->id . '.jpg');
		$path = str_replace("public/", "", $path);
		$mercado->url_imagen = $path;
		$mercado->save();

		DB::commit();

		return redirect('/mercados')->with('mensaje', 'Mercado registrado correctamente');

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Mercado  $mercado
	 * @return \Illuminate\Http\Response
	 */
	public function show(int $idMercado, Request $request)
	{
		if($request->query("childs", "no") == "no"){
			return Mercado::find($idMercado);
		}
		else{
			return Mercado::with("puestos")->find($idMercado);
		}

	}

	// public function showMercado(int $idMercado){
	// 	$mercado = Mercado::find($idMercado);
	// 	$puestos = Puesto::with('vendedor')->where('id_mercado', $mercado->id)->paginate(10);
		
	// 	return view('mercados.view', compact('mercado', 'puestos'));
	// }

	public function showMercado(int $idMercado){
		$mercado = Mercado::find($idMercado);
		$puestos = Puesto::with('vendedor')->where('id_mercado', $mercado->id)->paginate(10);
		
		return view('mercados.view', compact('mercado', 'puestos'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Mercado  $mercado
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Mercado $mercado)
	{
		
	}

	public function editMercado(int $idMercado){
		$mercado = Mercado::find($idMercado);
		return view('mercados.edit', compact('mercado'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Mercado  $mercado
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Mercado $mercado)
	{
		//
	}

	public function updateMercado(Request $request){
		$mercado = Mercado::find($request->id);
		
		$mercado->nombre = $request->nombre == '' ? $mercado->nombre : $request->nombre;
		$mercado->direccion = $request->direccion == '' ? $mercado->direccion : $request->direccion;
		$mercado->descripcion = $request->descripcion == '' ? $mercado->descripcion : $request->descripcion;

		if($request->has('foto')){
			unlink(storage_path("app/" . $mercado->url_imagen));
			$path = $request->foto->storeAs('public/images', 'mercado' . $mercado->id . '.jpg');
		}

		$mercado->save();

		return redirect('/mercados')->with('mensaje', 'Mercado editado correctamente');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Mercado  $mercado
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Mercado $mercado)
	{
		//
	}

	public function deleteMercado(int $idMercado){
		$mercado = Mercado::find($idMercado);
		$mercado->estado = 0;

		$mercado->save();

		return redirect('/mercados')->with('mensaje', 'Mercado eliminado correctamente');
	}

	public function uploadImageProfile(string $idMercado, Request $request)
	{
		if ($request->has('foto') && $idMercado != null) {
			$mercado = Mercado::find($idMercado);

			if (!empty($mercado)) {

				$path = $request->foto->storeAs('images', 'mercado' . $mercado->id . '.jpg');
				$mercado->url_imagen = $path;
				$mercado->save();

				$response['mensaje'] = "Imagen subida correctamente";
				
				return response()->json($response, Response::HTTP_CREATED);

			} else {
				$fault = new Fault("MER-01", "El mercado no existe");
				return response()->json($fault, Response::HTTP_BAD_REQUEST);
			}
		} else {
			$fault = new Fault(Constantes::$ERROR_API_EMPTY_CODE, Constantes::$ERROR_API_EMPTY_DESC, "foto, id_mercado");
			return response()->json($fault, Response::HTTP_BAD_REQUEST);
		}
	}
}
