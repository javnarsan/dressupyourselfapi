<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Compra;
use Validator;

class CompraController extends Controller { 
    public $successStatus = 200;

    public function index() {
        $compras = Compra::all();

        return response()->json(['Compras' => $compras->toArray()], $this->successStatus);
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
            return response()->json(['error' => $validator->errors()], 401);
        }

        return response()->json(['Compra' => $compra->toArray()], $this->successStatus);
    }


    public function update(Request $request, Compra $compra) {
        $input = $request->all();

        $validator = Validator::make($input, [
            'cliente_id' => 'required',
            'articulo_id' => 'required',
            'cantidad' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);       
        }

        $compra->cliente_id = $input['cliente_id'];
        $compra->articulo_id = $input['articulo_id'];
        $compra->cantidad = $input['cantidad'];
        $compra->save();

        return response()->json(['Compra' => $compra->toArray()], $this->successStatus);
    }

    public function destroy(Product $compra) {
        $compra->delete();

        return response()->json(['Compra' => $compra->toArray()], $this->successStatus);
    }
}
