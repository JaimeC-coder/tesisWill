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
        Schema::create('grados', function (Blueprint $table) {

            $table->bigIncrements('gra_id');
            $table->string('gra_descripcion', 255)->default('');
            $table->unsignedBigInteger('niv_id')->nullable();
            $table->char('gra_estado', 1)->default('1')->comment('1: Activo; 2: Inactivo');
            $table->char('gra_is_delete', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('niv_id')->references('niv_id')->on('nivels');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grados');
    }
};
