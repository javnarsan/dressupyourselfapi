<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('categoria');
            $table->text('modelo');
            $table->text('marca');
            $table->text('tipo');
            $table->text('talla');
            $table->text('genero')->nullable();
            $table->text('edad')->nullable();
            $table->text('foto',255)->nullable();
            $table->text('material')->nullable();
            $table->text('color')->nullable();
            $table->integer('stock');
            $table->integer('precio');
            $table->integer('vistas')->default(0);
            $table->boolean('deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articulos');
    }
}
