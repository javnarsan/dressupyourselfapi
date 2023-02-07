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

    //Lista filtrada por genero
    public function getByGenero($genero){
        $articulos = Articulo::where('genero', $genero)->get();
        return response()->json(['Articulos' => $articulos->toArray()], $this->successStatus);
    }
    //Lista filtrada por marca
    public function getByMarca($marca){
        $articulos = Articulo::where('marca', $marca)->get();
        return response()->json(['Articulos' => $articulos->toArray()], $this->successStatus);
    }
    
    //Incremento campo vistas
    public function updateVistas(Request $request, $id) {
        $articulo = Articulo::find($id);
    
        if (is_null($articulo)) {
            return response()->json(['error' => "Artículo no encontrado"], 404);
        }
    
        $articulo->vistas += 1;
        $articulo->save();
    
        return response()->json(['Articulo' => $articulo->toArray()], $this->successStatus);
    }






    
    public function store(Request $request) {
        $input = $request->all();

        $validator = Validator::make($input, [
            'modelo' => 'required',
            'marca' => 'required',
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
            'marca' => 'required',
            'tipo' => 'required',
            'stock' => 'required',
            'precio' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);       
        }

        $articulo->modelo = $input['modelo'];
        $articulo->marca = $input['marca'];
        $articulo->tipo = $input['tipo'];
        $articulo->stock = $input['stock'];
        $articulo->precio = $input['precio'];
        $articulo->save();

        return response()->json(['Articulo' => $articulo->toArray()], $this->successStatus);
    }

    public function destroy(Articulo $articulo) {
        $articulo->delete();

        return response()->json(['Articulo' => $articulo->toArray()], $this->successStatus);
    }
}
