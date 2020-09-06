<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Mercado;
use App\Model;
use Faker\Generator as Faker;

$factory->define(Mercado::class, function (Faker $faker) {
    return [
		"codigo_mercado" => 'MER-' . $faker->randomNumber($nbDigits = 3),
		"nombre" => $faker->company,
		"descripcion" => $faker->words(10, true),
		"direccion" => $faker->address
    ];
});
