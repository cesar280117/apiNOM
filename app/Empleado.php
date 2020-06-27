<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = [
        'primer_nombre',
        'segundo_nombre',
        'apellido_paterno',
        'apellido_materno',
        'estado_civil',
        'color_ojos',
        'color_cabello',
        'peso',
        'nacionalidad',
        'estatura',
        'religion',
        'nombre_emergecia',
        'telefono_emergencia',
        'celular_emergencia',
        'curp',
        'fecha_nacimiento',
        'lugar_nacimiento',
        'rfc',
        'domicilio',
        'email',
        'foto_empleado',
        'sexo',
        'donador',
        'padecimientos_medicos',
        'alergia',
        'nivel_estudios',
        'ocupacion',
        'tipo_puesto',
        'inicio_puesto',
        'departamento',
        'trabaja_actualmente',
        'tiempo_puesto',
        'tipo_contratacion',
        'tipo_personal',
        'id_jornada',
        'rotacion_turnos',
        'experiencia_puesto_actual',
        'experiencia_puesto_laboral',
        'hace_ejercicio',
        'salario',
        'estatus',
        'nivel_pago',
        'division',
        'rfc_empresa'
        
    ];
}
