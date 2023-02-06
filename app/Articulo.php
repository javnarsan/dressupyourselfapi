<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $fillable = [
        'modelo', 'tipo', 'genero', 'edad', 'material', 'color', 'stock', 'precio', 
    ];
}
