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
        Schema::create('asignar_cursos', function (Blueprint $table) {

            $table->bigIncrements('asig_id');
            $table->unsignedBigInteger('pa_id')->nullable();
            $table->unsignedBigInteger('niv_id')->nullable();
            $table->string('curso', 255)->nullable();
            $table->char('asig_is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('pa_id')->references('pa_id')->on('personal_academicos');
            $table->foreign('niv_id')->references('niv_id')->on('nivels');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignar_cursos');
    }
};
