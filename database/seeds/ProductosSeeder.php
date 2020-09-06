<?php

use App\Producto;
use App\Puesto;
use Illuminate\Database\Seeder;

class ProductosSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Puesto::all()->each(function($puesto){
			$productos = factory(Producto::class, 6)->create();

			foreach ($productos as $producto) {
				$producto->id_puesto = $puesto->id;
				$producto->save();
			}
		});
	}
}
