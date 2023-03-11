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
    //Lista filtrada por edad
    public function getByEdad($edad){
        $articulos = Articulo::where('edad', $edad)->get();
        return response()->json(['Articulos' => $articulos->toArray()], $this->successStatus);
    }
    //Incremento campo vistas
    public function updateVistas(Request $request, $modelo) {
        $articulos = Articulo::where('modelo', $modelo)->get();
        
        foreach ($articulos as $articulo) {
            $articulo->vistas += 1;
            $articulo->save();
        }
        
        return response()->json(['Articulos' => $articulos->toArray()], $this->successStatus);
    }

    //Mostrar catalogo
    public function showCatalogo(){
        $articulos = Articulo::select('modelo', 'marca', 'tipo', 'talla', 'edad', 'precio', 'foto')
                    ->distinct('modelo')
                    ->get();

        return response()->json(['Articulos' => $articulos->toArray()], $this->successStatus);

    }
    //Mostrar catalogo
    public function showCatalogoDestacados(){
        $articulos = Articulo::select('modelo', 'marca', 'tipo', 'talla', 'edad', 'precio', 'foto')
                    ->distinct('modelo')
                    ->orderByDesc('vistas')
                    ->get();

        return response()->json(['Articulos' => $articulos->toArray()], $this->successStatus);

    }


    //Info de tallas y stock de los articulos con ese mismo modelo
    public function showTallas($id)
    {
        $articulo = Articulo::find($id);

        if (is_null($articulo)) {
            return response()->json(['error' => 'Artículo no encontrado'], 404);
        }

        $model = $articulo->modelo;

        $articulosMismoModelo = Articulo::where('modelo', $model)->get();

        $tallasStock = $articulosMismoModelo->mapWithKeys(function ($articulo) {
            return [$articulo->talla => $articulo->stock];
        });

        return response()->json($tallasStock, $this->successStatus);
    }


    public function store(Request $request){
        $input = $request->all();

        $validator = Validator::make($input, [
            'modelo' => 'required',
            'marca' => 'required',
            'tipo' => 'required',
            'talla' => 'required',
            'stock' => 'required',
            'precio' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $articulo = Articulo::create($input);

        if ($request->hasFile('foto')) {
            $imagen = $request->file('foto');
            $nombre_archivo = $request->input('modelo') . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('imagenes'), $nombre_archivo);

            Articulo::where('modelo', $request->input('modelo'))->update(['foto' => $nombre_archivo]);
        }

        return response()->json(['Articulo' => $articulo->toArray()], $this->successStatus);
    }




    public function show($id) {
        $articulo = Articulo::find($id);

        if (is_null($articulo)) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        return response()->json(['Articulo' => $articulo->toArray()], $this->successStatus);
    }


    public function update(Request $request, $id) {
        $articulo = Articulo::find($id);
        $input = $request->all();

        $validator = Validator::make($input, [
            'modelo' => 'required',
            'marca' => 'required',
            'tipo' => 'required',
            'talla' => 'required',
            'stock' => 'required',
            'precio' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);       
        }

        $articulo->modelo = $input['modelo'];
        $articulo->marca = $input['marca'];
        $articulo->tipo = $input['tipo'];
        $articulo->talla = $input['talla'];
        $articulo->stock = $input['stock'];
        $articulo->precio = $input['precio'];
        $articulo->save();

        return response()->json(['Articulo' => $articulo->toArray()], $this->successStatus);
    }
}
