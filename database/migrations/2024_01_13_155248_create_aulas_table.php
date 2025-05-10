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
        Schema::create('aulas', function (Blueprint $table) {
            $table->bigIncrements('ala_id');
            $table->string('ala_descripcion', 255)->nullable();
            $table->string('ala_tipo', 255)->nullable();
            $table->integer('ala_aforo')->nullable();
            $table->string('ala_ubicacion', 255)->nullable();
            $table->char('ala_estado', 1)->default('1')->comment('1: Bueno; 2: Regular; 3: Malo');
            $table->char('is_multiuse', 1)->default('0')->comment('1: Si; 0: No');
            $table->char('ala_is_delete', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->char('ala_en_uso', 1)->default('2')->comment('1: Ocupada; 2: Vacia');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
