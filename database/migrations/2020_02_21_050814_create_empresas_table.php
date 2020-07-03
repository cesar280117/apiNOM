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
            $table->string('imagen',1000)->nullable();
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
