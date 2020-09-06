<?php

namespace App\Http\Controllers;

use App\CategoriaProductos;
use App\Puesto;
use App\ProductoPuesto;
use App\Fault;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PuestoController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */


//---------------------------------------------------
// fz
//---------------------------------------------------

public function puestoDetalle($id){
	// $puesto = Puesto::with('relacion')->find($id);
	// $puesto = Puesto::with(['productos','vendedor:id,nombres,apellidos,imagen_perfil'])->find($id);
	$puesto = Puesto::with(['mercado:id,nombre','productos:productos.id,productos.nombre,productos.descripcion,productos.precio,productos.stock,productos.url_imagen,productos.unidades','vendedor:id,nombres,apellidos,imagen_perfil'])
					->select('id','codigo','id_vendedor','id_mercado')
					->find($id);

	return response()->json([
		'success'=>true,
		'data'=>$puesto
	]);
}


//---------------------------------------------------
// end-fz
//---------------------------------------------------












	public function index(Request $request)
	{
		$noChild = $request->query("childs", "no") == "no";
		$idMercado = $request->query("id_mercado", "no");

		if ($noChild) {
			if ($idMercado == "no") {
				return Puesto::get()->each(function ($items) {
					$items->append('mercado');
				});
			} else {
				return Puesto::where(['id_mercado' => $idMercado])->get()->each(function ($items) {
					$items->append('mercado');
				});
			}
		} else {
			if ($idMercado == "no") {
				return Puesto::with(array(
					'vendedor' => function ($query) {
						$query->select('id', 'nombres', 'apellidos', 'imagen_perfil');
					}
					))->get()->each(function ($items) {
						$items->append('mercado');
						$items->append("productos");
					});
			} else {
				$puestos = Puesto::with(
					array(
						'vendedor' => function ($query) {
							$query->select('id', 'nombres', 'apellidos');
						}
					)
				)->where(["id_mercado" => $idMercado])->get()->each(function ($items) {
					$items->append('mercado');
					$items->append("productos");
				});

				foreach ($puestos as $key => $puesto) {
					$categorias = DB::select(DB::raw("SELECT cp.nombre
											FROM productos p,
												categoria_productos cp,
												producto_puestos pp 
											WHERE p.id_categoria = cp.id
											AND p.id = pp.id_producto
											AND pp.id_puesto = $puesto->id
											GROUP BY cp.nombre
											ORDER BY count(1) DESC
											LIMIT 3"));

					$max_categoria = null;
					foreach ($categorias as $cat) {
						if (empty($max_categoria)) {
							$max_categoria = ucwords($cat->nombre);
						} else {
							$max_categoria = $max_categoria . ', ' . ucwords($cat->nombre);
						}
					}

					$puestos[$key]->max_categorias = $max_categoria;
				}

				return $puestos;
			}
		}
	}

	public function indexPage()
	{
		$puestos = Puesto::with(array(
			'vendedor' => function ($query) {
				$query->select('id', 'nombres', 'apellidos');
			}
		))->paginate(5);

		return view('puestos.index', compact('puestos'));
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
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Puesto  $puesto
	 * @return \Illuminate\Http\Response
	 */
	public function show(int $idPuesto, Request $request)
	{
		if ($request->query("childs", "no") == "no") {
			$puesto = Puesto::find($idPuesto);
		} else {
			$puesto = Puesto::where('id',$idPuesto)->get()->each(function($items){
				$items->append("productos");
			})[0];
		}

		$categorias = DB::select(DB::raw("SELECT cp.nombre
											FROM productos p,
												categoria_productos cp,
												producto_puestos pp 
											WHERE p.id_categoria = cp.id
											AND p.id = pp.id_producto
											AND pp.id_puesto = $puesto->id
											GROUP BY cp.nombre
											ORDER BY count(1) DESC
											LIMIT 3"));

		$max_categoria = null;
		foreach ($categorias as $cat) {
			if (empty($max_categoria)) {
				$max_categoria = ucwords($cat->nombre);
			} else {
				$max_categoria = $max_categoria . ', ' . ucwords($cat->nombre);
			}
		}

		$puesto->max_categorias = $max_categoria;

		return $puesto;
	}

	public function showPuesto(int $idPuesto)
	{
		$puesto = Puesto::with(array(
			'mercado' => function ($query) {
				$query->select('id', 'nombre');
			},
			'vendedor' => function ($query) {
				$query->select('id', 'nombres', 'apellidos','imagen_perfil');
			}
		))->where('id', $idPuesto)->first();

		$productos = Producto::whereIn(
			'id', ProductoPuesto::where('id_puesto', $puesto->id)->get('id_producto')->map(function($datos){return $datos->id_producto;}) 
			)
			->paginate(10, ['id', 'nombre', 'unidades','precio','url_imagen']);

		return view('puestos.view', compact('puesto', 'productos'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Puesto  $puesto
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Puesto $puesto)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Puesto  $puesto
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Puesto $puesto)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Puesto  $puesto
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Puesto $puesto)
	{
		//
	}
}
