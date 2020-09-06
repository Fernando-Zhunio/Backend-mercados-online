<?php

use App\Puesto;
use App\Usuario;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class MercadoSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory(App\Mercado::class, 5)->create()->each(function ($mercado){

			$usuario = Usuario::where(['rol' => 'VENDEDOR'])->whereNotExists(function ($query){
				$query->select(DB::raw(1))
					->from('puestos')
					->whereRaw('puestos.id_vendedor = usuarios.id');
			})->first();

			if(!empty($usuario)){
				$datos['codigo'] = 'PT-' . random_int(0, 999);
				$datos['id_mercado'] = $mercado->id;
				$datos['id_vendedor'] = $usuario->id;

				Puesto::create($datos);
			}
		});
	}
}
