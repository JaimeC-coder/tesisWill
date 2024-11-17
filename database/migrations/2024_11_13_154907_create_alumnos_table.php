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
        Schema::create('alumnos', function (Blueprint $table) {

            $table->bigIncrements('alu_id');
            $table->unsignedBigInteger('per_id');
            $table->unsignedBigInteger('apo_id');
            $table->string('name_libreta_notas')->nullable();
            $table->string('name_ficha_matricula')->nullable();
            $table->char('alu_estado', 1)->default('0')->comment('1: Activo; 2:Inactivo; 3:Retirado');
            $table->char('is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('per_id')->references('per_id')->on('personas');
            $table->foreign('apo_id')->references('apo_id')->on('apoderados');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
