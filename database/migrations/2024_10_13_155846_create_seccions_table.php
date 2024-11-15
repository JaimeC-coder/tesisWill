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
        Schema::create('seccions', function (Blueprint $table) {

            $table->bigIncrements('sec_id');
            $table->string('sec_descripcion', 18)->nullable();
            $table->unsignedBigInteger('sec_tutor')->nullable();
            $table->unsignedBigInteger('sec_aula')->nullable();
            $table->unsignedBigInteger('gra_id')->nullable();
            $table->unsignedBigInteger('sec_periodo')->nullable();
            $table->integer('sec_vacantes')->nullable();
            $table->char('sec_is_delete', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('sec_tutor')->references('pa_id')->on('personal_academicos');
            $table->foreign('sec_aula')->references('ala_id')->on('aulas');
            $table->foreign('gra_id')->references('gra_id')->on('grados');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seccions');
    }
};
