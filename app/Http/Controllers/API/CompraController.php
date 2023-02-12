<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Compra;
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
            'articulo_id' => 'required',
            'cantidad' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);       
        }

        $compra = Compra::create($input);

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
        foreach ($compras as $compra) {
            $compra->fecha_compra = now();
            $compra->save();
        }
        return response()->json(['Compra' => $compras->toArray()], $this->successStatus);
    }

}
