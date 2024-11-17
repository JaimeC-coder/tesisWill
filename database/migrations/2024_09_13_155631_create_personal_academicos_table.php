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
        Schema::create('personal_academicos', function (Blueprint $table) {
            $table->bigIncrements('pa_id');
            $table->unsignedBigInteger('per_id')->nullable();
            $table->string('pa_turno', 255)->nullable();
            $table->string('pa_condicion_laboral', 255)->nullable();
            $table->unsignedBigInteger('niv_id')->nullable();
            $table->string('pa_especialidad', 255)->nullable();
            $table->unsignedBigInteger('rol_id')->nullable();
            $table->char('pa_is_tutor', 1)->default('0')->comment('1: Si es tutor; 2: No es tutor');
            $table->char('is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('per_id')->references('per_id')->on('personas');
            $table->foreign('niv_id')->references('niv_id')->on('nivels');
            $table->foreign('rol_id')->references('rol_id')->on('rols');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_academicos');
    }
};
