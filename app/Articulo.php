<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $fillable = [
        'modelo', 'tipo', 'talla', 'marca', 'genero', 'edad', 'material', 'color', 'stock', 'precio', 'foto', 'categoria'
    ];
    public function valoraciones()
    {
        return $this->hasMany('App\Valoracion');
    }
}
