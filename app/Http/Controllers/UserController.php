<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return response()->json([
            'total_usuarios' => count($usuarios),
            'usuarios' => $usuarios
        ], 200);
    }
    public function store(Request $request)
    {
        $this->validar($request);
        $datos = $request->all();
        $datos['password'] = Hash::make($datos['password']);

        $usuario = User::create($datos);
        return response()->json([
            'Mensaje' => 'Se creo con exito el usuario',
            'usuario' => $usuario
        ], 201);
    }
    public function update(Request $request, $id)
    {
        $usuario = User::find($id);
        if (!$usuario) {
            return response()->json([
                'Error' => 'No se encuentra el usuario con dicho id'
            ], 404);
        }
        $this->validar($request, $id);
        $datos = $request->all();
        $datos['password'] = Hash::make($datos['password']);

        $usuario->update($datos);
        return response()->json([
            'mensaje' => 'Se actualizo con exito el usuario',
            'usuario' => $usuario
        ], 201);
    }
    public function show($id)
    {
        $usuario = User::find($id);
        if (is_null($usuario)) {
            return response()->json([
                'Error' => 'No se encuentra usuario con dicho id.'
            ], 404);
        }
        return response()->json([
            'usuario' => $usuario
        ], 200);
    }
    public function destroy($id)
    {
        $usuario = User::find($id);
        if (is_null($usuario)) {
            return response()->json([
                'Error' => 'No se encuentra usuario con dicho id.'
            ], 404);
        }
        
            $usuario->delete();
            return response()->json([
                'mensaje' => 'Se elimino con exito el usuario.'
            ], 200);
       
    }
    public function login(Request $request)
    {
        $this->validate($request, [
            'usuario' => 'required|max:255',
            'password' => 'min:6|required'
        ]);

        $usuario = User::where('usuario', $request['usuario'])->first();
        if (Hash::check($request['password'], $usuario['password'])) {
            $usuario['api_token'] = Str::random(60);
            $usuario->save();
            return response()->json([
                'token' => $usuario['api_token']
            ], 200);
        }
        return response()->json([
            'Error' => 'Error de auntenticación. Favor de verificar el usuario o el password.'
        ], 404);
    }
    public function logout()
    {
        $usuario = Auth::user();
        $usuario['api_token'] = null;
        $usuario->save();

        return response()->json([
            'Mensaje' => 'Cerro sesión con exito.'
        ], 200);
    }
    public function auth()
    {
        $usuario = Auth::user();

        return response()->json([
            'usuario' => $usuario
        ], 200);
    }

    private function validar($request, $id = null)
    {
        $validar_modificar = is_null($id) ? '' : ',' . $id;
        $this->validate($request, [
            'nombre' => 'string|max:255|required',
            'usuario' => 'max:255|required|unique:users,usuario' . $validar_modificar,
            'email' => 'max:255|required|unique:users,email' . $validar_modificar,
            'password' => 'min:6|required',
            'c_password' => 'required|min:6|same:password'
        ]);
    }
}
