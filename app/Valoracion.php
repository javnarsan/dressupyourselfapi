<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Valoracion extends Model
{
    protected $fillable = ['user_id', 'articulo_id', 'comentario', 'puntuacion'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function articulo()  
    {
        return $this->belongsTo('App\Articulo');
    }
}
