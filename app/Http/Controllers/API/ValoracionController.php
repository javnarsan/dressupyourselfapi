<?php

namespace App\Http\Controllers\API;

use App\Valoracion;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Compra;
use App\User;
use App\Articulo;
use Illuminate\Http\Request;
use Validator;



class ValoracionController extends Controller
{
    public $successStatus = 200;
    public function getByArticuloId($id)
    {
        $valoraciones = Valoracion::with('user')->where('articulo_id', $id)->get();

        $valoracionesArray = $valoraciones->map(function ($valoracion) {
            return [
                'id' => $valoracion->id,
                'user_id' => $valoracion->user_id,
                'user_name' => $valoracion->user->nombre,
                'articulo_id' => $valoracion->articulo_id,
                'comentario' => $valoracion->comentario,
                'puntuacion' => $valoracion->puntuacion,
                'created_at' => $valoracion->created_at,
                'updated_at' => $valoracion->updated_at,
            ];
        });

        return response()->json(['Valoraciones' => $valoracionesArray], $this->successStatus);
    }



    public function store(Request $request)
    {
        if (Auth::user()->id != $request->get('user_id')) {
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

        $articulosMismoModelo = Articulo::where('modelo', Articulo::find($request->get('articulo_id'))->modelo)->where('deleted', '!=', 1)->get();
        
        foreach ($articulosMismoModelo as $articulo) {
            $valoracion = new Valoracion([
                'user_id' => $request->get('user_id'),
                'articulo_id' => $articulo->id,
                'comentario' => $request->get('comentario'),
                'puntuacion' => $request->get('puntuacion'),
            ]);

            $valoracion->save();
        }

        return response()->json([
            'message' => 'Valoraciones creadas con éxito',
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

        if (Auth::user()->tipo !== 'A' && Auth::user()->id != $valoracion->user_id) {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 401);
        }

        $articulo = Articulo::find($valoracion->articulo_id);
        $articulosMismoModelo = Articulo::where('modelo', $articulo->modelo)->get();

        foreach ($articulosMismoModelo as $articuloModelo) {
            $valoracionesArticulo = Valoracion::where('articulo_id', $articuloModelo->id)->delete();
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

        if (Auth::user()->tipo !== 'A' && Auth::user()->id != $valoracion->user_id) {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 401);
        }

        $articulosMismoModelo = Articulo::where('modelo', $valoracion->articulo->modelo)->get();

        $request->validate([
            'comentario' => 'nullable|string|max:255',
            'puntuacion' => 'nullable|integer|min:1|max:5',
        ]);

        foreach ($articulosMismoModelo as $articulo) {
            $valoracionModelo = Valoracion::where('articulo_id', $articulo->id)
                                        ->where('user_id', $valoracion->user_id)
                                        ->first();
            if ($valoracionModelo) {
                $valoracionModelo->comentario = $request->input('comentario', $valoracionModelo->comentario);
                $valoracionModelo->puntuacion = $request->input('puntuacion', $valoracionModelo->puntuacion);

                $valoracionModelo->save();
            }
        }

        return response()->json([
            'message' => 'Valoraciones actualizadas con éxito',
        ], 200);
    }


}
