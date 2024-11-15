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
            /**
             *  `nt_id` int(11) NOT NULL,
  `per_id` int(11) DEFAULT NULL,
  `alu_id` int(11) DEFAULT NULL,
  `pa_id` int(11) DEFAULT NULL,
  `ags_id` int(11) DEFAULT NULL,
  `nt_bimestre` int(11) DEFAULT NULL,
  `nt_nota` char(18) NOT NULL,
  `nt_is_deleted` char(1) DEFAULT '0' COMMENT '1: Eliminado; 0:No Eliminado',
  `curso_id` int(11) DEFAULT NULL
             */
            $table->bigIncrements('nt_id');
            $table->unsignedBigInteger('per_id')->nullable();
            $table->unsignedBigInteger('alu_id')->nullable();
            $table->unsignedBigInteger('pa_id')->nullable();
            $table->unsignedBigInteger('ags_id')->nullable();
            $table->unsignedBigInteger('curso_id')->nullable();
            $table->integer('nt_bimestre')->nullable();
            $table->string('nt_nota', 18)->default('');
            $table->char('nt_is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('per_id')->references('per_id')->on('personas');
            $table->foreign('alu_id')->references('alu_id')->on('alumnos');
            $table->foreign('pa_id')->references('per_id')->on('periodos');
            $table->foreign('ags_id')->references('ags_id')->on('gsas');
            $table->foreign('curso_id')->references('cur_id')->on('cursos');
            $table->softDeletes();
            $table->timestamps();
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
