<?php

namespace App\Http\Controllers;

use App\CategoriaProductos;
use App\Fault;
use App\Producto;
use App\ProductoNegocio;
use App\ProductoPuesto;
use App\Utils\Constantes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$tipo = $request->query("type", "ALL");
		$id = $request->query("id", NULL);

		switch($tipo){
			case 'ALL':
				$productos = Producto::all()->each(function($item){
					if($item->fuente == "PUESTO"){
						$item->append("id_puesto");
					}
					else if($item->fuente == "NEGOCIO"){
						$item->append("id_negocio");
					}
				});
				break;
			case 'PUESTO':
				$productos = Producto::with('puesto')->get();
				break;
			case 'TIENDA':
				$productos = Producto::whereIn('id', ProductoNegocio::where('id_negocio', $id)->get('id_producto'))
				->get()
				->each(function($item){
					$item->append("promocion");
				});
		}

		return $productos;
	}

	public function indexPage()
	{
		$productos = Producto::paginate(10);
		return view('productos.index', compact('productos'));
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
		$input['descripcion'] = $datos->descripcion;
		$input['precio'] = $datos->precio;
		$input['id_categoria'] = $datos->id_categoria;
		$input['unidades'] = $datos->unidades;
		$input['fuente'] = $datos->fuente;
		// $input['stock'] = $datos->stock;
		
		DB::beginTransaction();

		$producto = Producto::create($input);
		
		$relacion['id_producto'] = $producto->id;

		if($datos->fuente == "PUESTO"){
			$relacion['id_puesto'] = $datos->id_puesto;
			ProductoPuesto::create($relacion);
		}
		else if($datos->fuente == "NEGOCIO"){
			$relacion['id_negocio'] = $datos->id_negocio;
			$relacion['id_promocion'] = $datos->promocion;
			ProductoNegocio::create($relacion);
		}

		$path = $request->foto->storeAs('public/images', 'prod-' . $producto->id . '.jpg');
		$path = str_replace("public/", "", $path);
		$producto->url_imagen = $path;
		$producto->save();

		$response['id'] = $producto->id;
		$response['mensaje']  = "Producto registrado correctamente";

		DB::commit();

		return response()->json($response, Response::HTTP_CREATED);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Producto  $producto
	 * @return \Illuminate\Http\Response
	 */
	public function show(int $idProducto)
	{ }

	public function showProducto(int $idProducto)
	{
		$producto = Producto::find($idProducto);
		return view('productos.view', compact('producto'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Producto  $producto
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Producto $producto)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Producto  $producto
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, int $idProducto)
	{
		$producto = Producto::find($idProducto);

		$producto->nombre = $request->nombre;
		$producto->descripcion = $request->descripcion;
		$producto->precio = $request->precio;
		$producto->id_categoria = $request->id_categoria;
		$producto->unidades = $request->unidades;
		
		if($producto->fuente == "NEGOCIO"){
			$proNeg = ProductoNegocio::where('id_producto', $producto->id)->first();
			$proNeg->id_promocion = $request->promocion;
			$proNeg->save();
		}

		$producto->save();

		$response['id'] = $producto->id;
		$response['mensaje']  = "Producto editado correctamente";

		return response()->json($response, Response::HTTP_CREATED);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Producto  $producto
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(int $idProducto)
	{
		$producto = Producto::find($idProducto);

		if (!empty($producto)) {
			$producto->estado = 0;
			$producto->save();
			
			$response['mensaje'] = "Producto eliminado correctamente";
			return response()->json($response, Response::HTTP_OK);
		} else {
			$response['mensaje'] = "El producto no existe";
			return response()->json($response, Response::HTTP_BAD_REQUEST);
		}
	}

	public function indexCategorias()
	{
		return CategoriaProductos::all();
	}

	public function uploadImageProfile(string $idProducto, Request $request)
	{
		if ($request->has('foto') && $idProducto != null) {
			$producto = Producto::find($idProducto);

			if (!empty($producto)) {

				if($producto->url_imagen != null){
					unlink(storage_path("app/public/" . $producto->url_imagen));
				}

				$path = $request->foto->storeAs('public/images', 'prod-' . $producto->id . '.jpg');
				$path = str_replace("public/", "", $path);
				$producto->url_imagen = $path;
				$producto->save();

				$response['mensaje'] = "Imagen subida correctamente";

				return response()->json($response, Response::HTTP_CREATED);
			} else {
				$fault = new Fault("PRO-01", "El producto no existe");
				return response()->json($fault, Response::HTTP_BAD_REQUEST);
			}
		} else {
			$fault = new Fault(Constantes::$ERROR_API_EMPTY_CODE, Constantes::$ERROR_API_EMPTY_DESC, "foto, id_usuario");
			return response()->json($fault, Response::HTTP_BAD_REQUEST);
		}
	}
}
