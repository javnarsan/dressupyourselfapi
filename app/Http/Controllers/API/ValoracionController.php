<?php

namespace App\Http\Controllers\API;

use App\Valoracion;
use App\Http\Controllers\Controller;
use App\Compra;
use App\User;
use App\Articulo;
use Illuminate\Http\Request;
use Validator;



class ValoracionController extends Controller
{
    public function store(Request $request)
    {
        if (Auth::user()->id !== $request->get('user_id')) {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 401);
        }
        $compra = Compra::where('cliente_id', auth()->user()->id)
                    ->where('articulo_id', $request->articulo_id)
                    ->whereNotNull('fecha_compra')
                    ->first();
    
        if (!$compra) {
            return response()->json([
                'error' => 'Debes comprar este artículo antes de valorarlo.',
            ], 400);
        }
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

        if (Auth::user()->tipo !== 'A' && Auth::user()->id !== $valoracion->user_id) {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 401);
        }

        $valoracion->delete();

        return response()->json([
            'message' => 'Valoración eliminada con éxito',
        ], 200);
    }
 
    public function update(Request $request, $id)
    {
        $valoracion = Valoracion::find($id);

        if (!$valoracion) {
            return response()->json([
                'message' => 'No se encontró la valoración',
            ], 404);
        }

        if (Auth::user()->tipo !== 'A' && Auth::user()->id !== $valoracion->user_id) {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 401);
        }

        $request->validate([
            'comentario' => 'nullable|string|max:255',
            'puntuacion' => 'nullable|integer|min:1|max:5',
        ]);

        $valoracion->comentario = $request->input('comentario', $valoracion->comentario);
        $valoracion->puntuacion = $request->input('puntuacion', $valoracion->puntuacion);

        $valoracion->save();

        return response()->json([
            'message' => 'Valoración actualizada con éxito',
            'valoracion' => $valoracion
        ], 200);
    }


}
