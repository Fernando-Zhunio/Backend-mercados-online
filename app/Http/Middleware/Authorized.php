<?php

namespace App\Http\Middleware;

use App\Fault;
use App\Usuario;
use App\Utils\Constantes;
use Closure;
use Illuminate\Http\Response;

class Authorized
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$token = $request->bearerToken();

		if(!$request->hasHeader('rol')){
			$usuario = Usuario::where(['auth_token' => $token, 'estado' => 1])->first(['id']);
			
			if(empty($usuario)){
				$fault = new Fault("AUT-02", "Acceso no autorizado");
				return response()->json($fault, Response::HTTP_UNAUTHORIZED);
			}
		}

		return $next($request);
	}
}
