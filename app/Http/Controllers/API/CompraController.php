<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Compra;
use App\Articulo;
use Validator;

class CompraController extends Controller { 
    public $successStatus = 200;



    //Para obtener una lista de los articulos que tiene el usuario en el carrito
    public function getCarrito($id) {
        $compras = Compra::where('cliente_id', $id)
                        ->whereNull('fecha_compra')
                        ->with('articulo')
                        ->get();
    
        $articulos = $compras->map(function ($compra) {
            return $compra->articulo;
        });
    
        return response()->json(['Articulos' => $articulos->toArray()], $this->successStatus);
    }






    public function index() {
        $compras = Compra::all();

        return response()->json(['Compras' => $compras->toArray()], $this->successStatus);
    }

    public function articulosCompradosUser($id) {
        $compras = Compra::where('cliente_id', $id)
                        ->whereNotNull('fecha_compra')
                        ->with('articulo')
                        ->get();
    
        $articulos = $compras->map(function ($compra) {
            return $compra->articulo;
        });
    
        return response()->json(['Articulos' => $articulos->toArray()], $this->successStatus);
    }

    public function store(Request $request) {
        $input = $request->all();

        $validator = Validator::make($input, [
            'cliente_id' => 'required',
            'modelo' => 'required',
            'talla' => 'required',
            'cantidad' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);       
        }

        $articulo = Articulo::where('modelo', $input['modelo'])->where('talla', $input['talla'])->first();

        if (!$articulo) {
            return response()->json(['error' => 'El artículo no existe o no se encuentra disponible en esa talla'], 404);
        }

        $compra = Compra::create([
        'cliente_id' => $input['cliente_id'],
        'articulo_id' => $articulo->id,
        'cantidad' => $input['cantidad'],
        ]);

        return response()->json(['Compra' => $compra->toArray()], $this->successStatus);
    }

    public function show($id) {
        $compra = Compra::find($id);

        if (is_null($compra)) {
            return response()->json(['error' => 'Este usuario no ha realizado ninguna compra aun'], 401);
        }

        return response()->json(['Compra' => $compra->toArray()], $this->successStatus);
    }

    //Para confirmar la compra de un articulo
    public function confirmarCompra(Request $request, $id) {
        $compras = Compra::where('cliente_id', $id)->whereNull('fecha_compra')->get();
        $comprasEliminadas = [];
        foreach ($compras as $key => $compra) {
            if($compra->cantidad <= Articulo::find($compra->articulo_id)->stock){
                $compra->fecha_compra = now();
                $compra->save();
    
                // Actualizar el stock del artículo
                $articulo = Articulo::find($compra->articulo_id);
                $articulo->stock -= $compra->cantidad;
                $articulo->save();
            } else {
                $comprasEliminadas[] = $compra;
                unset($compras[$key]);
                $compra->delete();
            }
        }
        return response()->json([
            'Compras realizadas' => $compras->toArray(),
            'Compras eliminadas por problemas de stock' => $comprasEliminadas
        ], $this->successStatus);
    }
    //Eliminar del carrito
    public function eliminarDelCarrito($articulo_id) {
        $cliente_id = auth()->user()->id;
    
        $compra = Compra::where('cliente_id', $cliente_id)
                        ->where('articulo_id', $articulo_id)
                        ->whereNull('fecha_compra')
                        ->first();
    
        if (is_null($compra)) {
            return response()->json(['error' => 'El artículo no se encuentra en el carrito'], 404);
        }
    
        $compra->delete();
    
        return response()->json(['success' => 'El artículo ha sido eliminado del carrito'], $this->successStatus);
    }
    
    

}
