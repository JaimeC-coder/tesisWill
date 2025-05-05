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
        Schema::create('nota_capacidads', function (Blueprint $table) {
            $table->bigIncrements('nc_id');
            $table->string('nc_descripcion', 18)->nullable();
            $table->string('descripcion')->nullable();
            $table->string('nc_nota');
            $table->unsignedBigInteger('cap_id')->nullable();
            $table->unsignedBigInteger('nt_id')->nullable();

            $table->char('nc_is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('nt_id')->references('nt_id')->on('notas');
            $table->foreign('cap_id')->references('cap_id')->on('capacidads');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_capacidads');
    }
};
