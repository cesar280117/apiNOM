<?php

namespace App\Http\Controllers;

use App\Jornada;
use Illuminate\Http\Request;

class JornadaController extends Controller
{
    public function index()
    {
        $jornada = Jornada::all();
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
        $jornada = Jornada::create($datos);
        return response()->json([
            'Mensaje' => 'Se creo con exito la jornada.',
            'jornada' => $jornada
        ], 201);
    }
    public function show($id)
    {
        $jornada = Jornada::find($id);
        if (is_null($jornada)) {
            return response()->json([
                'Error' => 'No se encontro la jornada con dicho id.'
            ], 404);
        }
        return response()->json([
            'jornada' => $jornada,
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $jornada = Jornada::find($id);
        if (is_null($jornada)) {
            return response()->json([
                'Error' => 'No se encontro la jornada con dicho id.'
            ], 404);
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
        $jornada = Jornada::find($id);
        if (is_null($jornada)) {
            return response()->json([
                'Error' => 'No se encontro la jornada con dicho id.'
            ], 404);
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
