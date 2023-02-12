<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
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

$factory->define(Articulo::class, function (Faker $faker) {
    $tipos = ['Camisa', 'Pantalon', 'Camiseta', 'Chaqueta'];
    $tallas = ['XS', 'S', 'M', 'L', 'XL'];
    $marcas = ['Gucci', 'Louis Vuitton', 'DC','Adidas', 'Nike'];
    $generos = ['hombre', 'mujer'];
    $edades = ['joven','adulto','mayor','infantil'];
    $materiales = ['algodon', 'tela', 'vaquero', 'lino'];
    $colores = ['rojo', 'verde', 'azul','amarillo', 'marron'];
    

    return [

        'modelo' => $faker->sentence,
        'tipo' => $faker->randomElement($tipos),
        'talla' => $faker->randomElement($tallas),
        'marca' => $faker->randomElement($marcas),
        'genero' => $faker->randomElement($generos),
        'edad' => $faker->randomElement($edades),
        'material' => $faker->randomElement($materiales),
        'color' => $faker->randomElement($colores),
        'stock' => $faker->numberBetween(30, 50),
        'precio' => $faker->numberBetween(10, 200),
    ];
});
