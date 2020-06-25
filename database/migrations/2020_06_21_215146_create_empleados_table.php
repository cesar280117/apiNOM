<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('primer_nombre');
            $table->string('segundo_nombre')->nullable();
            $table->string('apellido_paterno');
            $table->string('apellido_materno')->nullable();
            $table->string('estado_civil');
            $table->string('color_ojos')->nullable();
            $table->string('color_cabello')->nullable();
            $table->decimal('peso', 8, 2);
            $table->string('nacionalidad');
            $table->decimal('estatura', 8, 2);
            $table->string('religion')->nullable();
            $table->string('nombre_emergecia')->nullable();
            $table->string('telefono_emergencia')->nullable();
            $table->string('celular_emergencia')->nullable();
            $table->string('curp', 18)->unique();
            $table->string('fecha_nacimiento');
            $table->string('lugar_nacimiento');
            $table->string('rfc')->unique();
            $table->string('domicilio');
            $table->string('email')->unique();
            $table->text('foto_empleado')->nullable();
            $table->string('sexo');
            $table->string('donador')->nullable();
            $table->string('padecimientos_medicos');
            $table->string('alergia');
            $table->string('nivel_estudios');
            $table->string('ocupacion');
            $table->string('tipo_puesto');
            $table->string('departamento');
            $table->string('trabaja_actualmente');
            $table->string('tiempo_puesto')->nullable();
            $table->string('inicio_puesto')->nullable();
            $table->string('tipo_contratacion');
            $table->string('tipo_personal');
            $table->unsignedBigInteger('id_jornada');
            $table->string('rotacion_turnos');
            $table->string('experiencia_puesto_actual');
            $table->string('experiencia_puesto_laboral');
            $table->string('hace_ejercicio');
            $table->decimal('salario', 8, 2);
            $table->string('estatus');
            $table->string('nivel_pago');
            $table->string('division');
            $table->timestamps();

            $table->foreign('id_jornada')->references('id')->on('jornadas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleados');
    }
}
