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
    //Lista filtrada por categoria
    public function getByCategoria($categoria){
        $articulos = Articulo::where('categoria', $categoria)->get();
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
        $articulos = Articulo::select('modelo', 'marca', 'tipo', 'talla', 'edad', 'precio', 'foto', 'categoria')
                    ->distinct('modelo')
                    ->get();

        return response()->json(['Articulos' => $articulos->toArray()], $this->successStatus);

    }
    //Mostrar catalogo
    public function showCatalogoDestacados(){
        $articulos = Articulo::select('modelo', 'marca', 'tipo', 'talla', 'edad', 'precio', 'foto','categoria')
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


    public function store(Request $request)
    {
        if (Auth::user()->tipo !== 'A') {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 401);
        }
        $input = $request->all();

        $validator = Validator::make($input, [
            'modelo' => 'required',
            'marca' => 'required',
            'tipo' => 'required',
            'talla' => 'required',
            'stock' => 'required',
            'precio' => 'required',
            'genero' => 'required',
            'edad' => 'required',
            'material' => 'required',
            'color' => 'required',
            'categoria' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $articulo = Articulo::where('modelo', $request->input('modelo'))->first();

        if ($articulo) {
            $nombre_archivo = $articulo->foto;
        } else {
            $articulo = new Articulo;
            $articulo->modelo = $request->input('modelo');
            $articulo->marca = $request->input('marca');
            $articulo->tipo = $request->input('tipo');
            $articulo->talla = $request->input('talla');
            $articulo->stock = $request->input('stock');
            $articulo->precio = $request->input('precio');
            $articulo->genero = $request->input('genero');
            $articulo->edad = $request->input('edad');
            $articulo->material = $request->input('material');
            $articulo->color = $request->input('color');
            $articulo->categoria = $request->input('categoria');
            if ($request->hasFile('foto')) {
                $imagen = $request->file('foto');
                $nombre_archivo = $request->input('modelo') . '.' . $imagen->getClientOriginalExtension();
                $imagen->move(public_path('imagenes'), $nombre_archivo);
                $articulo->foto = $nombre_archivo;
                Articulo::where('modelo', $request->input('modelo'))->update(['foto' => $nombre_archivo]);
            }else{
                $articulo->foto = null;
            }
            
            $articulo->save();
            return response()->json(['Articulo' => $articulo->toArray()], $this->successStatus);
        }

        $articulo_nuevo = new Articulo;
        $articulo_nuevo->modelo = $request->input('modelo');
        $articulo_nuevo->marca = $request->input('marca');
        $articulo_nuevo->tipo = $request->input('tipo');
        $articulo_nuevo->talla = $request->input('talla');
        $articulo_nuevo->stock = $request->input('stock');
        $articulo_nuevo->precio = $request->input('precio');
        $articulo_nuevo->genero = $request->input('genero');
        $articulo_nuevo->edad = $request->input('edad');
        $articulo_nuevo->material = $request->input('material');
        $articulo_nuevo->color = $request->input('color');
        $articulo_nuevo->categoria = $request->input('categoria');
        $articulo_nuevo->foto = $nombre_archivo;
        $articulo_nuevo->save();

        return response()->json(['Articulo' => $articulo_nuevo->toArray()], $this->successStatus);
    }






    public function show($id) {
        $articulo = Articulo::find($id);

        if (is_null($articulo)) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        return response()->json(['Articulo' => $articulo->toArray()], $this->successStatus);
    }


    public function update(Request $request, $id) {
        if (Auth::user()->tipo !== 'A') {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 401);
        }
        $articulo = Articulo::find($id);
        $input = $request->all();

        $validator = Validator::make($input, [
            'modelo' => 'required',
            'marca' => 'required',
            'tipo' => 'required',
            'talla' => 'required',
            'stock' => 'required',
            'precio' => 'required',
            'categoria' => 'required',
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
        $articulo->categoria = $input['categoria'];
        $articulo->save();

        return response()->json(['Articulo' => $articulo->toArray()], $this->successStatus);
    }

    public function softDelete($id)
    {
        $articulo = Articulo::find($id);

        if (!$articulo) {
            return response()->json(['message' => 'Artículo no encontrado'], 404);
        }

        if (Auth::user()->tipo !== 'A') {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 401);
        }

        $articulo->deleted = 1;
        $articulo->save();

        return response()->json(['message' => 'Artículo eliminado correctamente'], 200);
    }

}
