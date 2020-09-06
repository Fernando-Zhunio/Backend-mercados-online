<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Usuario;
use Faker\Generator as Faker;

$factory->define(Usuario::class, function (Faker $faker) {
    return [
		'usuario' => $faker->userName,
		'password' => Hash::make('12345'),
		'auth_token' => Hash::make($faker->userName . '12345' . date('YmdHis')),
		'email' => $faker->email,
		'nombres' => $faker->name,
		'apellidos' => $faker->lastName,
		'direccion' => $faker->address,
		'celular' => $faker->phoneNumber,
		'rol' => $faker->randomElement(['CLIENTE','VENDEDOR','TRANSPORTISTA'])
    ];
});
