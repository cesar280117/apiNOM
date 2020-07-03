<?php

namespace App\Http\Controllers;

use App\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empresa = Auth::user()->rfc;
        $empleados = DB::table('empleados')
            ->where('empleados.rfc_empresa', $empresa)
            ->join('jornadas', 'empleados.id_jornada', 'jornadas.id')
            ->join('empresas', 'empleados.rfc_empresa', 'empresas.rfc')
            ->select(
                'empleados.*',
                'jornadas.jornada',
                'empresas.nombre_empresa'
            )
            ->get();
        return response()->json([
            'total_empleados' => count($empleados),
            'empleados' => $empleados
        ], 200);
    }
    public function store(Request $request)
    {
        $this->validar($request);
        $datos = $request->all();
        if ($request->hasFile('foto_empleado')) {
            $datos['foto_empleado'] = $this->save_image($datos['foto_empleado']);
        } else {
            $datos['foto_empleado'] = 'default.PNG';
        }
        $datos['rfc_empresa'] = Auth::user()->rfc;
        $empleado = Empleado::create($datos);
        return response()->json([
            'mensaje' => 'se registro con exito el empleado',
            'empleado' => $empleado
        ], 201);
    }
    public function show($id)
    {
        $empresa = Auth::user()->rfc;
        $empleado = Empleado::find($id);
        if (is_null($empleado)) {
            return response()->json([
                'Error' => 'no se encontro el empleado con dicho id.'
            ], 404);
        }
        if ($empresa != $empleado['rfc_empresa']) {
            return response()->json([
                'Error' => 'Acceso prohibido'
            ], 403);
        }
        $empleado = DB::table('empleados')
            ->where('empleados.id', $id)
            ->join('jornadas', 'empleados.id_jornada', 'jornadas.id')
            ->join('empresas', 'empleados.rfc_empresa', 'empresas.frc')
            ->select(
                'empleados.*',
                'jornadas.jornada',
                'empresas.nombre_empresa'
            )
            ->first();
        return response()->json([
            'empleado' => $empleado
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $empresa = Auth::user()->rfc;
        $empleado = Empleado::find($id);
        if (is_null($empleado)) {
            return response()->json([
                'Error' => 'no se encontro el empleado con dicho id.'
            ], 404);
        }
        if ($empresa != $empleado['rfc_empresa']) {
            return response()->json([
                'Error' => 'Acceso prohibido'
            ], 403);
        }
        $this->validar($request, $id);
        $datos = $request->all();
        if ($request->hasFile('foto_empleado')) {
            if ($empleado['foto_empleado'] != 'default.PNG') {
                unlink(base_path() . '/public/images/empleados/' . $empleado['foto_empleado']);
            }
            $datos['foto_empleado'] = $this->save_image($datos['foto_empleado']);
        }
        $empleado->update($datos);
        return response()->json([
            'mensaje' => 'se actualizo con exito el empleado.',
            'empleado' => $empleado
        ], 201);
    }
    public function destroy($id)
    {
        $empresa = Auth::user()->frc;
        $empleado = Empleado::find($id);

        if (is_null($empleado)) {
            return response()->json([
                'Error' => 'no existe empleado con dicho id.'
            ], 404);
        }
        if ($empresa != $empleado['rfc_empresa']) {
            return response()->json([
                'Error' => 'Acceso prohibido'
            ], 403);
        }
        if ($empleado['foto_empleado'] != 'default.PNG') {

            unlink(base_path() . '/public/images/empleados/' . $empleado['foto_empleado']);
        }

        $empleado->delete();
        return response()->json([
            'mensaje' => 'Se elimino con exito el empleado'
        ], 201);
    }

    private function validar($request, $id = null)
    {

        $no_repetir_modificado = is_null($id) ? '' : ',' . $id;
        $this->validate($request, [
            'primer_nombre' => 'required|string|max:255',
            'segundo_nombre' => 'max:255|string|nullable',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'max:255|string|nullable',
            'estado_civil' => 'required|string|max:255',
            'color_ojos' => 'max:255|string|nullable',
            'color_cabello' => 'max:255|string|nullable',
            'peso' => 'required|numeric',
            'nacionalidad' => 'required|string|max:255',
            'estatura' => 'required|numeric',
            'religion' => 'max:255|string|nullable',
            'nombre_emergecia' => 'max:255|string|nullable',
            'curp' => 'required|max:18|unique:empleados,curp' . $no_repetir_modificado,
            'fecha_nacimiento' => 'required|max:10|nullable',
            'lugar_nacimiento' => 'required|max:255|string',
            'rfc' => 'required|unique:empleados,rfc' . $no_repetir_modificado,
            'domicilio' => 'required|max:255|string',
            'email' => 'required|unique:empleados,email' . $no_repetir_modificado,
            'foto_empleado' => 'max:1000|image|nullable',
            'sexo' => 'required|max:10|string',
            'donador' => 'max:255|string|nullable',
            'padecimientos_medicos' => 'required|max:255|string',
            'alergia' => 'required|max:255|string',
            'nivel_estudios' => 'required|max:255|string',
            'ocupacion' => 'required|max:255|string',
            'tipo_puesto' => 'max:255|string|nullable',
            'departamento' => 'required|max:255|string',
            'trabaja_actualmente' => 'required|max:255|string',
            'tiempo_puesto' => 'required|max:255|string',
            'tipo_contratacion' => 'required|max:255|string',
            'tipo_personal' => 'required|max:255|string',
            'id_jornada' => 'required|numeric|exists:jornadas,id',
            'rotacion_turnos' => 'required|max:255|string',
            'experiencia_puesto_actual' => 'required|max:255|string',
            'experiencia_puesto_laboral' => 'required|max:255|string',
            'hace_ejercicio' => 'required|max:255|string',
            'salario' => 'required|numeric',
            'estatus' => 'required|max:255|string',
            'nivel_pago' => 'required|max:255|string',
            'division' => 'required|max:255|string',

        ]);
    }

    private function save_image($foto)
    {
        $nombre_foto = time() . '_' . $foto->getClientOriginalName();
        $foto->move(base_path() . '/public/images/empleados', $nombre_foto);
        return $nombre_foto;
    }
}
