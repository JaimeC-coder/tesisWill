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
        Schema::create('institucions', function (Blueprint $table) {
            $table->bigIncrements('ie_id');
            $table->string('ie_codigo_modular', 255)->nullable();
            $table->string('ie_anexo', 255)->nullable();
            $table->string('ie_nivel', 255)->nullable();
            $table->string('ie_nombre', 255)->nullable();
            $table->string('ie_gestion', 255)->nullable();
            $table->string('ie_departamento', 255)->nullable();
            $table->string('ie_provincia', 255)->nullable();
            $table->string('ie_distrito', 255)->nullable();
            $table->string('ie_direccion', 255)->nullable();
            $table->string('ie_dre', 255)->nullable();
            $table->string('ie_ugel', 255)->nullable();
            $table->string('ie_genero', 255)->nullable();
            $table->string('ie_turno', 255)->nullable();
            $table->string('ie_dias_laborales', 255)->nullable();
            $table->string('ie_director', 255)->nullable();
            $table->string('ie_telefono', 255)->nullable();
            $table->string('ie_email', 255)->nullable();
            $table->string('ie_web', 255)->nullable();
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
        Schema::dropIfExists('institucions');
    }
};
