<?php

namespace App\Http\Controllers;

use App\Jornada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JornadaController extends Controller
{
    public function index()
    {
        $empresa = Auth::user()->rfc;
        $jornada = DB::table('jornadas')
            ->where('jornadas.rfc_empresa', $empresa)
            ->join('empresas', 'jornadas.rfc_empresa', 'empresas.rfc')
            ->select(
                'jornadas.id',
                'jornadas.turno',
                'jornadas.hora_inicial',
                'jornadas.hora_final',
                'jornadas.jornada',
                'jornadas.rfc_empresa',
                'empresas.nombre_empresa'
            )
            ->get();
        return response()->json([
            'total_jornadas' => count($jornada),
            'jornadas' => $jornada
        ], 200);
    }
    public function store(Request $request)
    {
        $this->validar($request);
        $datos = $request->all();
        $datos['jornada'] = 'fijo ' . $datos['turno'] . ' entre las ' . $datos['hora_inicial'] . ' y ' . $datos['hora_final'] . ' hrs ';
        $datos['rfc_empresa'] = Auth::user()->rfc;
        $jornada = Jornada::create($datos);
        return response()->json([
            'Mensaje' => 'Se creo con exito la jornada.',
            'jornada' => $jornada
        ], 201);
    }
    public function show($id)
    {
        $empresa = Auth::user()->rfc;
        $jornada = Jornada::find($id);
        if (is_null($jornada)) {
            return response()->json([
                'Error' => 'No se encontro la jornada con dicho id.'
            ], 404);
        }
        if ($empresa != $jornada['rfc_empresa']) {
            return response()->json([
                'Error' => 'Acceso prohibido.'
            ], 403);
        }

        $jornada = DB::table('jornadas')
            ->where('jornadas.id', $id)
            ->join('empresas', 'jornadas.rfc_empresa', 'empresas.rfc')
            ->select(
                'jornadas.id',
                'jornadas.turno',
                'jornadas.hora_inicial',
                'jornadas.hora_final',
                'jornadas.jornada',
                'jornadas.rfc_empresa',
                'empresas.nombre_nombre_empresa'
            )
            ->first();
        return response()->json([
            'jornada' => $jornada
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $empresa = Auth::user()->rfc;
        $jornada = Jornada::find($id);
        if (is_null($jornada)) {
            return response()->json([
                'Error' => 'No se encontro la jornada con dicho id.'
            ], 404);
        }
        if ($empresa != $jornada['rfc_empresa']) {
            return response()->json([
                'Error' => 'Acceso prohibido.'
            ], 403);
        }
        $this->validar($request);
        $datos = $request->all();
        $datos['jornada'] = 'fijo ' . $datos['turno'] . ' entre las ' . $datos['hora_inicial'] . ' y ' . $datos['hora_final'] . ' hrs ';
        $jornada->update($datos);
        return response()->json([
            'mensaje' => 'Se modifico la jornada con exito',
            'jornada' => $jornada
        ], 200);
    }
    public function destroy($id)
    {
        $empresa = Auth::user()->rfc;
        $jornada = Jornada::find($id);
        if (is_null($jornada)) {
            return response()->json([
                'Error' => 'No se encontro la jornada con dicho id.'
            ], 404);
        }
        if ($empresa != $jornada['rfc_empresa']) {
            return response()->json([
                'Error' => 'Acceso prohibido.'
            ], 403);
        }

        $jornada->delete();
        return response()->json([
            'mensaje' => 'Se elimino la jornada con exito',
        ], 200);
    }
    private function validar($request)
    {
        $this->validate($request, [
            'turno' => 'required|string|max:255',
            'hora_inicial' => 'required|max:255',
            'hora_final' => 'required|max:255'
        ]);
    }
}
