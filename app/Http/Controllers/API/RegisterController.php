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
            'name' => 'required',
            'surname' => 'required',
            'direccion' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);       
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['name'] =  $user->name;
        $success['surname'] =  $user->surname;
        $success['direccion'] =  $user->direccion;
        $success['email'] =  $user->email;

        return response()->json(['success' => $success], $this->successStatus);
    }

    public function login() {
        // Si las credenciales son correctas
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            // Creamos un token de acceso para ese usuario
            $success['token'] = $user->createToken('MyApp')->accessToken;

            // Y lo devolvemos en el objeto 'json'
            return response()->json(['success' => $success], $this->successStatus);
        }
        else {
            return response()->json(['error' => 'No estás autorizado'], 401);
        }
    }
    public function getUsers() {
        $users = User::all();
        return response()->json(['users' => $users], $this->successStatus);
    }

}