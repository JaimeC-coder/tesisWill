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
        Schema::create('notas', function (Blueprint $table) {
            $table->bigIncrements('nt_id');
            $table->unsignedBigInteger('per_id')->nullable();
            $table->unsignedBigInteger('alu_id')->nullable();
            $table->unsignedBigInteger('pa_id')->nullable();
            $table->unsignedBigInteger('ags_id')->nullable();
            $table->unsignedBigInteger('curso_id')->nullable();
            $table->integer('nt_bimestre')->nullable();
            $table->string('nt_nota', 18)->default('');
            $table->char('nt_is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('per_id')->references('per_id')->on('periodos');
            $table->foreign('alu_id')->references('alu_id')->on('alumnos');
            $table->foreign('pa_id')->references('pa_id')->on('personal_academicos');
            $table->foreign('ags_id')->references('ags_id')->on('gsas');
            $table->foreign('curso_id')->references('cur_id')->on('cursos');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
