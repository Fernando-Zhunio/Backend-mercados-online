<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('usuarios')->insert([
			'usuario' => 'merOnl2020Adm',
			'password' => Hash::make('M3rK20line_2020'),
			'auth_token' => Hash::make('merOnl2020Adm' . 'M3rK20line_2020' . date('YmdHis')),
			'email' => 'support@mercados-online.com',
			'nombres' => 'El',
			'apellidos' => 'Administrador',
			'direccion' => 'Sauces VIII',
			'celular' => '593990274714',
			'rol' => 'ADMIN'
		]);

		// factory(App\Usuario::class, 10)->create();
	}
}
