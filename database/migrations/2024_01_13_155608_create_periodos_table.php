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
        Schema::create('periodos', function (Blueprint $table) {
            $table->bigIncrements('per_id');
            $table->unsignedBigInteger('anio_id')->nullable();
            $table->date('per_inicio_matriculas')->nullable();
            $table->date('per_final_matricula')->nullable();
            $table->date('per_limite_cierre')->nullable();
            $table->integer('per_tp_notas')->nullable();
            $table->char('per_estado', 1)->default('0')->comment('1: Aperturado; 2:Finalizado');
            $table->char('is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('anio_id')->references('anio_id')->on('anios');
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodos');
    }
};
