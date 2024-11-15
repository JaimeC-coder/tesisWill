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
        Schema::create('cursos', function (Blueprint $table) {
            $table->bigIncrements('cur_id');
            $table->string('cur_nombre', 50)->default('0');
            $table->string('cur_abreviatura', 50)->default('0');
            $table->integer('cur_horas')->default(0);
            $table->unsignedBigInteger('gra_id')->nullable();
            $table->unsignedBigInteger('niv_id')->nullable();
            $table->unsignedBigInteger('per_id')->nullable();
            $table->string('cur_estado', 50)->default('1')->comment('1: Activo; 2: Inactivo');
            $table->char('is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('gra_id')->references('gra_id')->on('grados');
            $table->foreign('niv_id')->references('niv_id')->on('nivels');
            $table->foreign('per_id')->references('per_id')->on('periodos');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
