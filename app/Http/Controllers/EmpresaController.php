<?php

namespace App\Http\Controllers;

use App\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class EmpresaController extends Controller
{
    public function index()
    {
        return response([
            'empresa' => Auth::user()
        ], 200);
    }
    public function store(Request $request)
    {

        $this->validar($request);
        $datos = $request->all();
        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $this->save_imagen($datos['imagen']);
        } else {
            $datos['imagen'] = 'default.PNG';
        }
        $datos['password'] = Hash::make($datos['password']);
        $empresa = Empresa::create($datos);
        return response()->json([
            'mensaje' => 'Se registro con exito la empresa',
            'empresa' => $empresa
        ], 201);
    }
    public function update(Request $request, $rfc)
    {
        $empresa = Empresa::find($rfc);
        if (is_null($empresa)) {
            return response()->json([
                'Error' => 'No se encuentra empresa registrada con dicho RFC.'
            ], 404);
        }
        if (Auth::user()->rfc != $rfc) {
            return response()->json([
                'Error' => 'Acceso prohibido.'
            ], 403);
        }

        $this->validar($request, $rfc);
        $datos = $request->all();
        if ($request->hasFile('imagen')) {
            if ($empresa['imagen'] != 'default.PNG') {
                unlink(base_path() . '/public/images/empresas/' . $empresa['imagen']);
            }
            $datos['imagen'] = $this->save_imagen($datos['imagen']);
        } else {
            $datos['imagen'] = $empresa['imagen'];
        }
        if (is_null($datos['password'])) {
            $datos['password'] = $empresa['password'];
        } else {
            $datos['password'] = Hash::make($datos['password']);
        }

        $empresa->update($datos);
        return response()->json([
            'mensaje' => 'Se modifico con exito la empresa.',
            'empresa' => $empresa
        ], 201);
    }
    public function show($rfc)
    {
        $empresa = Empresa::find($rfc);
        if (is_null($empresa)) {
            return response()->json([
                'Error' => 'No se encuentra empresa registrada con dicho RFC.'
            ], 404);
        }
        if (Auth::user()->rfc != $rfc) {
            return response()->json([
                'Error' => 'Acceso prohibido.'
            ], 403);
        }

        return response()->json([
            'empresa' => $empresa
        ], 200);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'rfc' => 'required|max:13',
            'password' => 'required|min:6'
        ]);

        $empresa = Empresa::where('rfc', $request['rfc'])->first();
        if (Hash::check($request['password'], $empresa['password'])) {
            $empresa['api_token'] = Str::random(60);
            $empresa->save();

            return response()->json([
                'token' => $empresa['api_token'],
                'empresa' => $empresa['nombre_empresa'],
                'rfc' => $empresa['rfc'],
                'imagen' => $empresa['imagen']
            ], 200);
        }
        return response()->json([
            'Error' => 'No se encuentra empresa con el RFC solicitado. Favor de verificar.'
        ], 401);
    }
    public function logout()
    {
        $empresa = Auth::user();
        $empresa['api_token'] = null;
        $empresa->save();
        return response()->json([
            'mensaje' => 'Se cerro la sesión con exito.'
        ], 200);
    }
    public function auth()
    {
        return response()->json([
            'empresa' => Auth::user()
        ], 200);
    }

    private function validar($request, $rfc = null)
    {

        $validar_modificar = is_null($rfc) ? '' : ',' . $rfc . ',rfc';
        $password = is_null($rfc) ? 'required|min:8' : 'min:8|nullable';
        $c_password = is_null($rfc) ? 'required|same:password' : 'same:password';
        $this->validate($request, [
            'rfc' => 'required|max:13|unique:empresas,rfc' . $validar_modificar,
            'nombre_empresa' => 'required|max:255|unique:empresas,nombre_empresa' . $validar_modificar,
            'domicilio' => 'required|max:255',
            'telefono' => 'required|numeric|unique:empresas,telefono' . $validar_modificar,
            'curp' => 'max:18|nullable|unique:empresas,curp' . $validar_modificar,
            'imagen' => 'nullable|max:1000|image',
            'password' => $password,
            'c_password' => $c_password
        ]);
    }

    private function save_imagen($foto)
    {
        $nombreEMpresa = time() . '_' . $foto->getClientOriginalName();
        $foto->move(base_path() . '/public/images/empresas', $nombreEMpresa);
        return $nombreEMpresa;
    }
}
