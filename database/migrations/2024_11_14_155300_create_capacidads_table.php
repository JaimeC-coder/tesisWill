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
        Schema::create('capacidads', function (Blueprint $table) {
       
            $table->bigIncrements('cap_id');
            $table->string('cap_descripcion', 255)->default('');
            $table->unsignedBigInteger('cur_id')->nullable();
            $table->integer('cap_is_deleted')->default(0)->comment('1: Eliminado; 0:No Eliminado');
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
        Schema::dropIfExists('capacidads');
    }
};
