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
                'Error' => 'No se puede acceder a dicha información.'
            ], 500);
        }

        $this->validar($request, $rfc);
        $datos = $request->all();
        $datos['password'] = Hash::make($datos['password']);
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
                'Error' => 'No se puede acceder a dicha información.'
            ], 500);
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
                'token' => $empresa['api_token']
            ], 200);
        }
        return response()->json([
            'Error' => 'No se encuentra empresa con el RFC solicitado. Favor de verificar.'
        ], 404);
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
        $this->validate($request, [
            'rfc' => 'required|max:13|unique:empresas,rfc' . $validar_modificar,
            'nombre_empresa' => 'required|max:255|unique:empresas,nombre_empresa'.$validar_modificar,
            'domicilio' => 'required|max:255',
            'telefono' => 'required|numeric|unique:empresas,telefono' . $validar_modificar,
            'curp' => 'max:18|unique:empresas,curp' . $validar_modificar,
            'numero_acreditacion' => 'required|numeric',
            'numero_aprobacion' => 'required|numeric',
            'datos_dictamen' => 'required|max:255|string',
            'clave_norma' => 'required|numeric',
            'nombre_norma' => 'required|string|max:255',
            'nombre_verificador' => 'required|max:255',
            'fecha_verificacion' => 'required|max:255',
            'numero_dictamen' => 'required|numeric',
            'luegar_emicion_dictamen' => 'required|max:255|string',
            'fecha_emicion_dictamen' => 'required|max:255|string',
            'numero_registro_dictamen' => 'required|numeric',
            'metodos_factores_riesgo' => 'required',
            'vigencia_dictamenes_emitidos' => 'required|max:255|string',
            'numero_total_trabajadores' => 'required|numeric',
            'numero_trabajadores_entrevistar' => 'required|numeric',
            'numero_trabajadores_entrevistados' => 'required|numeric',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password'
        ]);
    }
}
