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
        Schema::create('apoderados', function (Blueprint $table) {
    
            $table->bigIncrements('apo_id');
            $table->unsignedBigInteger('per_id')->nullable();
            $table->string('apo_parentesco', 50)->nullable();
            $table->char('apo_vive_con_estudiante', 1)->default('1')->comment('1: SI; 2:No');
            $table->foreign('per_id')->references('per_id')->on('personas');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apoderados');
    }
};
