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
        Schema::create('anios', function (Blueprint $table) {


            $table->bigIncrements('anio_id');
            $table->string('anio_descripcion', 50)->default('0');
            $table->date('anio_fechaInicio')->nullable();
            $table->date('anio_fechaFin')->nullable();
            $table->string('anio_duracionHoraAcademica', 255)->nullable();
            $table->string('anio_duracionHoraLibre', 255)->nullable();
            $table->integer('anio_cantidadPersonal')->nullable();
            $table->char('anio_tallerSeleccionable', 1)->nullable()->comment('1: SI; 2: NO');
            $table->char('anio_estado', 1)->nullable()->comment('1: Activo; 2: Inactivo');
            $table->char('is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anios');
    }
};
