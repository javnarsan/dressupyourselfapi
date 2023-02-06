<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Articulo;
use Validator;

class ArticuloController extends Controller { 
    public $successStatus = 200;

    public function index() {
        $articulos = Articulo::all();

        return response()->json(['Articulos' => $articulos->toArray()], $this->successStatus);
    }

    public function getByGenero($genero){
    $articulos = Articulo::where('genero', $genero)->get();
    return response()->json(['Articulos' => $articulos->toArray()], $this->successStatus);
    }

    public function store(Request $request) {
        $input = $request->all();

        $validator = Validator::make($input, [
            'modelo' => 'required',
            'tipo' => 'required',
            'stock' => 'required',
            'precio' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);       
        }

        $articulo = Articulo::create($input);

        return response()->json(['Articulo' => $articulo->toArray()], $this->successStatus);
    }

    public function show($id) {
        $articulo = Articulo::find($id);

        if (is_null($articulo)) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        return response()->json(['Articulo' => $articulo->toArray()], $this->successStatus);
    }


    public function update(Request $request, Articulo $articulo) {
        $input = $request->all();

        $validator = Validator::make($input, [
            'modelo' => 'required',
            'tipo' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);       
        }

        $articulo->name = $input['modelo'];
        $articulo->detail = $input['tipo'];
        $articulo->save();

        return response()->json(['Articulo' => $articulo->toArray()], $this->successStatus);
    }

    public function destroy(Product $articulo) {
        $articulo->delete();

        return response()->json(['Articulo' => $articulo->toArray()], $this->successStatus);
    }
}
