<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Valoracion;
use Faker\Generator as Faker;

$factory->define(App\Valoracion::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\User::class),
        'articulo_id' => factory(App\Articulo::class),
        'comentario' => $faker->text,
        'puntuacion' => $faker->numberBetween(1, 5),
    ];
});
