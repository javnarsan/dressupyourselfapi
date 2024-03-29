<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory(\App\User::class, 20)->create();
        factory(\App\Articulo::class, 20)->create();
        factory(\App\Compra::class, 20)->create();
        factory(\App\Valoracion::class, 50)->create();
    }
}
