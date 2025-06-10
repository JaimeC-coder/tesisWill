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
        Schema::create('matriculas', function (Blueprint $table) {
            $table->bigIncrements('mat_id');
            $table->unsignedBigInteger('per_id')->nullable();
            $table->unsignedBigInteger('alu_id')->nullable();
            $table->unsignedBigInteger('ags_id')->nullable();
            $table->date('mat_fecha')->nullable();
            $table->string('mat_situacion', 255)->nullable();
            $table->string('mat_tipo_procedencia', 255)->nullable();
            $table->string('mat_colegio_procedencia', 255)->nullable();
            $table->string('mat_observacion', 255)->nullable();
            $table->char('mat_estado', 1)->default('1')->comment('1: Activa; 2: Inactiva');
            $table->char('is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('per_id')->references('per_id')->on('periodos');
            $table->foreign('alu_id')->references('alu_id')->on('alumnos');
            $table->foreign('ags_id')->references('ags_id')->on('gsas');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
