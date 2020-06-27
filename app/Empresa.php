<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Empresa extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rfc',
        'nombre_empresa',
        'domicilio',
        'telefono',
        'curp',
        'numero_acreditacion',
        'numero_aprobacion',
        'datos_dictamen',
        'clave_norma',
        'nombre_norma',
        'nombre_verificador',
        'fecha_verificacion',
        'numero_dictamen',
        'luegar_emicion_dictamen',
        'fecha_emicion_dictamen',
        'numero_registro_dictamen',
        'metodos_factores_riesgo',
        'vigencia_dictamenes_emitidos',
        'numero_total_trabajadores',
        'numero_trabajadores_entrevistar',
        'numero_trabajadores_entrevistados',
        'password',
        'api_token'
    ];

    protected $keyType = 'string';
    protected $primaryKey = 'rfc';
    public $incrementing = false;
    

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'created_at',
        'updated_at'
    ];
}
