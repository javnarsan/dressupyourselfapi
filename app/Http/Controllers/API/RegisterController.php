<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class RegisterController extends Controller {
    public $successStatus = 200;

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellidos' => 'required',
            'direccion' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);       
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['message'] =  'Usuario registrado correctamente';

        return response()->json(['success' => $success], $this->successStatus);
    }

    public function login() {
        $credentials = [
            'email' => request('email'),
            'password' => request('password')
        ];
    
        $user = User::where('email', $credentials['email'])->first();
    
        if (!$user) {
            return response()->json(['error' => 'El correo electrónico no existe'], 401);
        }
    
        if (Auth::attempt($credentials)) {
            $success['id'] = $user->id;
            $success['tipo'] = $user->tipo;
            $success['token'] = $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error' => 'La contraseña es incorrecta'], 401);
        }
    }

    public function getUsers() {
        $users = User::all();
        return response()->json(['users' => $users], $this->successStatus);
    }
    public function show($id) {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
    
        return response()->json(['user' => $user], 200);
    }
    public function softDelete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        if (Auth::user()->tipo !== 'A') {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 401);
        }

        $user->deleted = 1;
        $user->save();

        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
    }

    public function update(Request $request, $id) {
        $user = User::find($id);
        $input = $request->all();

        $validator = Validator::make($input, [
            'nombre' => 'required',
            'apellidos' => 'required',
            'direccion' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);       
        }

        $user->nombre = $input['nombre'];
        $user->apellidos = $input['apellidos'];
        $user->tipo = $user->tipo;
        $user->direccion = $input['direccion'];
        $user->email = $user->email;
        $user->password = $user->password;
        $user->save();

        return response()->json(['User' => $user->toArray()], $this->successStatus);
    }
}