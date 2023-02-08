<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Compra;
use App\User;
use App\Articulo;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Compra::class, function (Faker $faker) {
    return [
        'cantidad' => 2,
        'cliente_id' => \App\User::all()->random()->id,
        'articulo_id' => \App\Articulo::all()->random()->id,
    ];
});
