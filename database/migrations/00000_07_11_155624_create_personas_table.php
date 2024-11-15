<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->bigIncrements('per_id');
            $table->char('per_dni', 8)->nullable();
            $table->string('per_nombres', 255)->nullable();
            $table->string('per_apellidos', 255)->nullable();
            $table->string('per_nombre_completo', 255)->nullable();
            $table->string('per_email', 255)->nullable();
            $table->string('per_sexo', 50)->nullable()->comment('M: Masculino; F:Femenino');
            $table->date('per_fecha_nacimiento')->nullable();
            $table->string('per_estado_civil', 50)->default('0')->comment('S: Soltero; C: Casado; D: Divorciado; V: Viudo');
            $table->string('per_celular', 50)->nullable();
            $table->string('per_pais', 50)->nullable();
            $table->integer('per_departamento')->nullable();
            $table->integer('per_provincia')->nullable();
            $table->integer('per_distrito')->nullable();
            $table->string('per_direccion', 255)->nullable();
            $table->char('is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
