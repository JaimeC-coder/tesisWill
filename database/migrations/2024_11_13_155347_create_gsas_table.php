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
        Schema::create('gsas', function (Blueprint $table) {
            $table->bigIncrements('ags_id');
            $table->unsignedBigInteger('ala_id')->nullable();
            $table->unsignedBigInteger('niv_id')->nullable();
            $table->unsignedBigInteger('gra_id')->nullable();
            $table->unsignedBigInteger('sec_id')->nullable();
            $table->char('is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('ala_id')->references('alu_id')->on('alumnos');
            $table->foreign('niv_id')->references('niv_id')->on('nivels');
            $table->foreign('gra_id')->references('gra_id')->on('grados');
            $table->foreign('sec_id')->references('sec_id')->on('seccions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gsas');
    }
};
