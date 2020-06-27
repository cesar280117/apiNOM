<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->string('rfc')->primary();
            $table->string('nombre_empresa')->unique();
            $table->string('domicilio');
            $table->string('telefono')->unique();
            $table->string('curp')->nullable();
            $table->bigInteger('numero_acreditacion');
            $table->bigInteger('numero_aprobacion');
            $table->string('datos_dictamen');
            $table->string('clave_norma');
            $table->string('nombre_norma');
            $table->string('nombre_verificador');
            $table->string('fecha_verificacion');
            $table->bigInteger('numero_dictamen');
            $table->string('luegar_emicion_dictamen');
            $table->string('fecha_emicion_dictamen');
            $table->bigInteger('numero_registro_dictamen');
            $table->text('metodos_factores_riesgo');
            $table->string('vigencia_dictamenes_emitidos');
            $table->bigInteger('numero_total_trabajadores');
            $table->bigInteger('numero_trabajadores_entrevistar');
            $table->bigInteger('numero_trabajadores_entrevistados');
            $table->string('password');
            $table->string('api_token', 60)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
