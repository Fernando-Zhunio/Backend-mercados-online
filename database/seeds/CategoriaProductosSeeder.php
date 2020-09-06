<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaProductosSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('categoria_productos')->insert(['nombre' => 'MARISCOS']);
		DB::table('categoria_productos')->insert(['nombre' => 'VIVERES']);
		DB::table('categoria_productos')->insert(['nombre' => 'PAPELERIA']);
		DB::table('categoria_productos')->insert(['nombre' => 'CARNES']);
		DB::table('categoria_productos')->insert(['nombre' => 'MARISCOS']);
		DB::table('categoria_productos')->insert(['nombre' => 'VERDURAS']);
		DB::table('categoria_productos')->insert(['nombre' => 'VARIOS']);
	}
}
