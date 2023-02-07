<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable = [
        'cliente_id', 'articulo_id', 'cantidad', 
    ];

    public function articulo()
    {
        return $this->belongsTo('App\Articulo');
    }
}
