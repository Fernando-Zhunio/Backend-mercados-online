<?php

namespace App\Http\Controllers;

use App\DetallePedido;
use App\Fault;
use App\Pedido;
use App\ProductoNegocio;
use App\ProductoPuesto;
use App\Puesto;
use App\Tienda;
use App\Usuario;
use App\Utils\Constantes;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$tipoUsuarios = $request->query("tipo", "ALL");

		switch ($tipoUsuarios) {
			case 'ALL':
				$datos = Usuario::all();
				break;
			case 'CLIENTE':
				$datos = Usuario::where('rol', 'CLIENTE')->get();
				break;
			case 'VENDEDOR':
				$datos = Usuario::where('rol', 'VENDEDOR')->get();
				break;
			case 'TRANSPORTISTA':
				$datos = Usuario::where('rol', 'TRANSPORTISTA')->get();
				break;
			default:
				$datos = Usuario::all();
				break;
		}
		return response()->json($datos, Response::HTTP_OK);
	}

	public function indexPage(Request $request)
	{
		$type = $request->query("type", "all");

		switch ($type) {
			case 'all':
				$usuarios = Usuario::where([
					['rol', '<>', 'ADMIN'],
					['estado', '=', 1]
				])->paginate(5);
				break;
			case 'cliente':
				$usuarios = Usuario::where('rol', 'CLIENTE')->paginate(5);
				break;
			case 'vendedor':
				$usuarios = Usuario::where('rol', 'VENDEDOR')->paginate(5);
				break;
			case 'transportista':
				$usuarios = Usuario::where('rol', 'TRANSPORTISTA')->paginate(5);
				break;
			case 'tiendero':
				$usuarios = Usuario::where('rol', 'TIENDERO')->paginate(5);
				break;
		}

		return view('usuarios.index', compact('usuarios', 'type'));
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

	public function createUser()
	{
		return view('usuarios.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$input['usuario'] = $request->get('usuario');
		$input['password'] = Hash::make($request->get('password'));
		$input['email'] = $request->get('email');
		$input['nombres'] = $request->get('nombres');
		$input['apellidos'] = $request->get('apellidos');
		$input['direccion'] = $request->get('direccion');
		$input['celular'] = $request->get('celular');
		$input['rol'] = $request->get('rol');
		
		DB::beginTransaction();

		try {
			$usuario = Usuario::create($input);

			$token = Hash::make($input['usuario'] . $input['password'] . date('YmdHis'));
			$usuario->auth_token = $token;
			$usuario->save();
			$response['id'] = $usuario->id;
			$response['token'] = $token;

			if ($request->get('rol') == 'VENDEDOR') {
				$inpPuesto['codigo'] = 'PT-' . $request->get('puesto');
				$inpPuesto['id_mercado'] = $request->get('id_mercado');
				$inpPuesto['id_vendedor'] = $usuario->id;

				$puesto = Puesto::create($inpPuesto);

				$response['id_puesto'] = $puesto->id;
			}

			
			$response['mensaje'] = "Usuario creado exitosamente";

			DB::commit();

			return response()->json($response, Response::HTTP_CREATED);
		} catch (QueryException $e) {
			DB::rollback();

			if ($e->getCode() == 23000) {
				if (strpos($e->getMessage(), "usuarios_email_unique")) {
					$fault = new Fault("NEG-01", "El email ingresado ya se encuentra registrado");
				} else if (strpos($e->getMessage(), "usuarios_usuario_unique")) {
					$fault = new Fault("NEG-02", "El usuario ingresado ya se encuentra registrado");
				}else{
					$fault = new Fault("API-99", "Ocurrio un error desconocido -> " . $e->getMessage());
				}

				return response()->json($fault, Response::HTTP_BAD_REQUEST);
			}

			Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
		} catch (Exception $e) {
			DB::rollback();
			$fault = new Fault("API-99", "Ocurrio un error desconocido");
			Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
			return response()->json($fault, Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	public function storeUsuario(Request $request)
	{
		$input['nombres'] = $request->nombres;
		$input['apellidos'] = $request->apellidos;
		$input['direccion'] = $request->direccion;
		$input['celular'] = $request->celular;
		$input['email'] = $request->email;

		$max = Usuario::where('rol', 'TRANSPORTISTA')->select('id')->count();

		$input['usuario'] = 'transportista' . ($max + 1);
		$input['password'] = Hash::make($input['usuario'] . '2020');
		$input['rol'] = 'TRANSPORTISTA';

		try {
			$usuario = Usuario::create($input);
			$token = Hash::make($input['usuario'] . $input['password'] . date('YmdHis'));
			$usuario->auth_token = $token;
			$usuario->save();
		} catch (QueryException $e) {
			if ($e->getCode() == 23000) {
				if (strpos($e->getMessage(), "usuarios_email_unique")) {
					return redirect()->back()->withErrors(['error' => "El email ingresado ya se encuentra registrado"]);
				} else if (strpos($e->getMessage(), "usuarios_usuario_unique")) {
					return redirect()->back()->withErrors(['error' => "El usuario ingresado ya se encuentra registrado"]);
				}
			}
		}

		return redirect('/usuarios')->with('mensaje', "Transportista registrado correctamente [ " . $usuario->usuario . "/" . $usuario->usuario . '2020]');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Usuario  $usuario
	 * @return \Illuminate\Http\Response
	 */
	public function show(int $usuario)
	{
		$datos = Usuario::find($usuario);

		if (empty($datos)) {
			$fault = new Fault(Constantes::$ERROR_USER_NO_EXISTE_CODE, Constantes::$ERROR_USER_NO_EXISTE_DESC);
			return response()->json($fault, Response::HTTP_NOT_FOUND);
		} else {
			return $datos;
		}
	}

	public function showUsuario(int $idUsuario){
		$usuario = Usuario::with(array(
			'puesto' => function($query){
				$query->select('id', 'id_vendedor', 'codigo', 'id_mercado');
			},
			'puesto.mercado' => function($query){
				$query->select('id', 'nombre');
			}
		))->where('id', $idUsuario)->first();

		$negocios = array();

		if($usuario->rol == "TIENDERO"){
			$negocios = Tienda::where('id_usuario', $usuario->id)->paginate(5);
		}

		return view('usuarios.view', compact('usuario', 'negocios'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Usuario  $usuario
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Usuario $usuario)
	{ }

	public function editUsuario(int $idUsuario){
		$usuario = Usuario::find($idUsuario);

		return view('usuarios.edit', compact('usuario'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Usuario  $usuario
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Usuario $usuario)
	{
		//
	}

	public function updateUsuario(Request $request){
		$usuario = Usuario::find($request->id);
		$usuario->nombres = $request->nombres == "" ? $usuario->nombres : $request->nombres;
		$usuario->apellidos = $request->apellidos == "" ? $usuario->apellidos : $request->apellidos;
		$usuario->direccion = $request->direccion == "" ? $usuario->direccion : $request->direccion;
		$usuario->celular = $request->celular == "" ? $usuario->celular : $request->celular;
		$usuario->email = $request->email == "" ? $usuario->email : $request->email;

		try {
			$usuario->save();
		} catch (QueryException $e) {
			if ($e->getCode() == 23000) {
				if (strpos($e->getMessage(), "usuarios_email_unique")) {
					return redirect()->back()->withErrors(['error' => "El email ingresado ya se encuentra registrado"]);
				} else if (strpos($e->getMessage(), "usuarios_usuario_unique")) {
					return redirect()->back()->withErrors(['error' => "El usuario ingresado ya se encuentra registrado"]);
				}
			}
		}
		return redirect('/usuarios')->with('mensaje', "Transportista editado correctamente");
	}

	public function updateUser(Request $request,$id)
	{
		$user = Usuario::find($id);
		$ruler = collect([
			'nombres'=>'required|string',
			'apellidos'=>'required|string',
			'direccion'=>'required|string',
			'celular'=>'required|string'
		]);
		
		$validation= null;
		$rol = $user->rol;
		if($rol !="CLIENTE" && $rol!='TRANSPORTISTA' && $rol!='VENDEDOR'){
			return response()->json([
				'success'=>false,
				'error'=>['message'=>'Este usuario no se puede modificar'],
				'data'=>$rol
			]);	
		}
		else{
			if($rol == 'VENDEDOR')
			$ruler = $ruler->merge(['mercado_id'=>'integer|exists:mercados,id','puesto_id'=>'integer']);
		}

		$validation = Validator::make($request->all(),$ruler->all());	
		if($validation->fails()){
            return response()->json(array(
                'success' => false,
                'errors' => $validation->getMessageBag()->toArray(),
            ));
		}
		
		$user->nombres=$request->nombres;
		$user->apellidos=$request->apellidos;
		$user->direccion=$request->direccion;
		$user->celular=$request->celular;

		$puesto=null;
		if($rol == 'VENDEDOR')
		{
			if($request->hasAny(['mercado_id','puesto_id'])){
				$puesto = $user->puesto();
				$puesto = Puesto::where('id_vendedor',$user->id)->first();
				if($request->filled('mercado_id'))$puesto->id_mercado = $request->mercado_id ;
				if($request->filled('puesto_id'))$puesto->id_puesto = $request->puesto_id ;
				$puesto->update();
			}
		}
		$user->save();
		return response()->json([
			'success'=>true,
			'usuario'=>$user,
			'puesto'=>$puesto
		]);
	
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Usuario  $usuario
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Usuario $usuario)
	{
		//
	}

	public function deleteUsuario(int $idUsuario){
		$usuario = Usuario::find($idUsuario);
		if($usuario->rol == "CLIENTE"){
			$pedidos = Pedido::where('id_usuario', $idUsuario)->get('id')->toArray();
			DetallePedido::whereIn('id_venta', $pedidos)->delete();
			Pedido::whereIn('id', $pedidos)->delete();
		}
		else if($usuario->rol == "VENDEDOR"){
			DetallePedido::where('id_vendedor', $idUsuario)->delete();
			$puesto = Puesto::where('id_vendedor', $idUsuario)->first();
			ProductoPuesto::where('id_puesto', $puesto->id)->delete();
			$puesto->delete();
		}
		else if($usuario->rol == "TIENDERO"){
			DetallePedido::where('id_vendedor', $idUsuario)->delete();
			$negocios = Tienda::where('id_usuario', $idUsuario)->get('id')->toArray();
			ProductoNegocio::whereIn('id_negocio', $negocios)->delete();
			Tienda::whereIn('id', $negocios)->delete();
		}

		$usuario->delete();

		return redirect('/usuarios')->with('mensaje', 'Usuario eliminado correctamente');
	}

	public function uploadImageProfile(string $idUsuario, Request $request)
	{
		if ($request->has('foto') && $idUsuario != null) {
			$usuario = Usuario::find($idUsuario);

			if (!empty($usuario)) {

				$path = $request->foto->storeAs('public/images', 'profile-' . $usuario->id . '.jpg');
				$path = str_replace("public/", "", $path);
				$usuario->imagen_perfil = $path;
				$usuario->save();

				$response['mensaje'] = "Imagen subida correctamente";

				return response()->json($response, Response::HTTP_CREATED);
			} else {
				$fault = new Fault(Constantes::$ERROR_USER_NO_EXISTE_CODE, Constantes::$ERROR_USER_NO_EXISTE_DESC);
				return response()->json($fault, Response::HTTP_BAD_REQUEST);
			}
		} else {
			$fault = new Fault(Constantes::$ERROR_API_EMPTY_CODE, Constantes::$ERROR_API_EMPTY_DESC, "foto, id_usuario");
			return response()->json($fault, Response::HTTP_BAD_REQUEST);
		}
	}
}
