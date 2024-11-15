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
        Schema::create('horarios', function (Blueprint $table) {
            $table->bigIncrements('hor_id');
            $table->unsignedBigInteger('per_id')->nullable();
            $table->unsignedBigInteger('ags_id')->nullable();
            $table->unsignedBigInteger('cur_id')->nullable();
            $table->date('fecha')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->string('color', 50)->nullable();
            $table->string('editable', 50)->nullable();
            $table->integer('is_deleted')->default(0)->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('per_id')->references('per_id')->on('periodos');
            $table->foreign('ags_id')->references('ags_id')->on('gsas');
            $table->foreign('cur_id')->references('cur_id')->on('cursos');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
