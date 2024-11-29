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
        Schema::create('tipos', function (Blueprint $table) {
            $table->bigIncrements('tp_id');
            $table->string('tp_tipo', 255)->nullable();
            $table->char('is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos');
    }
};
