<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Producto;
use Faker\Generator as Faker;


$factory->define(Producto::class, function (Faker $faker) {
	$faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

    return [
		"id_puesto" => 0,
		"nombre" => $faker->productName,
		"descripcion" => $faker->words(6, true),
		"precio" => $faker->randomFloat(2, 0.10, 100),
		"id_categoria" => $faker->numberBetween(1, 6),
		"url_imagen" => null
    ];
});
