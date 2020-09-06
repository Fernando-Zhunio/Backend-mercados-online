<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaNegociosSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('categoria_negocios')->insert(['nombre' => 'TIENDAS']);
		DB::table('categoria_negocios')->insert(['nombre' => 'RESTAURANTES']);
		DB::table('categoria_negocios')->insert(['nombre' => 'FERRETERIAS']);
		DB::table('categoria_negocios')->insert(['nombre' => 'BAZARES']);
	}
}
