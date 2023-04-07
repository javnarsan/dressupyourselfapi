<?php

namespace App\Http\Controllers;

use App\Valoracion;
use App\User;
use App\Articulo;
use Illuminate\Http\Request;



class ValoracionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'articulo_id' => 'required|integer|exists:articulos,id',
            'comentario' => 'nullable|string|max:255',
            'puntuacion' => 'required|integer|min:1|max:5',
        ]);

        $valoracion = new Valoracion([
            'user_id' => $request->get('user_id'),
            'articulo_id' => $request->get('articulo_id'),
            'comentario' => $request->get('comentario'),
            'puntuacion' => $request->get('puntuacion'),
        ]);

        $valoracion->save();

        return response()->json([
            'message' => 'Valoración creada con éxito',
            'valoracion' => $valoracion
        ], 201);
    }

    public function destroy($id)
    {
        $valoracion = Valoracion::find($id);

        if (!$valoracion) {
            return response()->json([
                'message' => 'No se encontró la valoración',
            ], 404);
        }

        $valoracion->delete();

        return response()->json([
            'message' => 'Valoración eliminada con éxito',
        ], 200);
    }


}
