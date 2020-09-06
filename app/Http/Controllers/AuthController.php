<?php

namespace App\Http\Controllers;

use App\Fault;
use App\Puesto;
use App\Tienda;
use App\Usuario;
use App\Utils\Constantes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

	public function index()
	{
		if (session('isAuthenticated')) {
			return redirect('/');
		}

		return view('auth.login');
	}

	public function authenticate(Request $request)
	{
		$input = $request->all();
		if ($request->has('usuario') && $request->has('password')) {
			$usuario = Usuario::where(['usuario' => $input['usuario'], 'estado' => 1])
				->whereIn('rol', ['CLIENTE', 'VENDEDOR', 'TRANSPORTISTA', 'TENDERO'])
				->first(['id', 'nombres', 'password', 'apellidos', 'direccion', 'celular', 'email', 'rol','imagen_perfil']);
			if (!empty($usuario)) {
				if (Hash::check($input['password'], $usuario->password)) {
					$token = Hash::make($input['usuario'] . $input['password'] . date('YmdHis'));

					$usuario->auth_token = $token;
					$usuario->save();

					$usuario->makeHidden(['password', 'auth_token']);
					$usuario->token = $token;

					if ($usuario->rol == "VENDEDOR") {
						$usuario->id_puesto = Puesto::where('id_vendedor', $usuario->id)->first('id')->id;
					}
					else if($usuario->rol == "TIENDERO"){
						$usuario->negocios = Tienda::where('id_usuario', $usuario->id)->get(['id','nombre','direccion','creditos_totales']);
					}

					return response()->json($usuario, Response::HTTP_OK);
				} else {
					$fault = new Fault(Constantes::$ERROR_AUTH_CODE, Constantes::$ERROR_AUTH_DESC);
					return response()->json($fault, Response::HTTP_UNAUTHORIZED);
				}
			} else {
				$fault = new Fault(Constantes::$ERROR_AUTH_CODE, Constantes::$ERROR_AUTH_DESC);
				return response()->json($fault, Response::HTTP_UNAUTHORIZED);
			}
		} else {
			$fault = new Fault(Constantes::$ERROR_API_EMPTY_CODE, Constantes::$ERROR_API_EMPTY_DESC, "usuario, password");
			return response()->json($fault, Response::HTTP_BAD_REQUEST);
		}
	}

	public function authenticateAdmin(Request $request)
	{
		$input = $request->all();

		if ($request->has('usuario') && $request->has('password')) {
			$usuario = Usuario::where(['usuario' => $input['usuario'], 'estado' => 1])
				->whereIn('rol', ['ASESOR', 'ADMIN'])
				->first(['id', 'nombres', 'password', 'apellidos', 'direccion', 'celular', 'email', 'rol', 'auth_token']);

			if (!empty($usuario)) {

				if (Hash::check($input['password'], $usuario->password)) {
					$token = Hash::make($input['usuario'] . $input['password'] . date('YmdHis'));

					$usuario->auth_token = $token;
					$usuario->save();

					$usuario->makeHidden(['password', 'auth_token', 'id']);
					$usuario->token = $token;

					session()->put('isAuthenticated', true);
					session()->put('id_usuario', $usuario->id);
					session()->put('token', $usuario->auth_token);
					session()->put('nombre_completo', $usuario->nombres . ' ' . $usuario->apellidos);

					return redirect('/');
				} else {
					return redirect()->back()->withErrors(['error' => Constantes::$ERROR_AUTH_DESC]);
				}
			} else {
				return redirect()->back()->withErrors(['error' => Constantes::$ERROR_AUTH_DESC]);
			}
		} else {
			return redirect()->back()->withErrors(['error' => "El usuario y contraseña es requerido"]);
		}
	}

	public function saveGcToken(Request $request)
	{
		$input = $request->all();

		$usuario = Usuario::find($input['id_usuario']);

		if (empty($usuario)) {
			$fault = new Fault(Constantes::$ERROR_USER_NO_EXISTE_CODE, Constantes::$ERROR_USER_NO_EXISTE_DESC);
			return response()->json($fault, Response::HTTP_BAD_REQUEST);
		} else {
			$usuario->gc_token = $input['token'];
			$usuario->save();

			$response['token'] = $input['token'];

			return response()->json($response, Response::HTTP_CREATED);
		}
	}

	public function logout()
	{
		session()->remove('isAuthenticated');
		session()->remove('id_usuario');
		session()->remove('token');
		session()->remove('nombre_completo');

		return redirect('/');
	}
}
