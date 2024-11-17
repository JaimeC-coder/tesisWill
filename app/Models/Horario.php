<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Horario extends Model
{
    use SoftDeletes;
    /**
     *    Schema::create('horarios', function (Blueprint $table) {
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
            $table->timestamps();
            $table->softDeletes();
        });
     */

    protected $table = 'horarios';
    protected $primaryKey = 'hor_id';
    protected $fillable = [
        'per_id',
        'ags_id',
        'cur_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'color',
        'editable',
        'is_deleted'
    ];


    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'per_id', 'per_id');
    }

    public function gsa()
    {
        return $this->belongsTo(Gsa::class, 'ags_id', 'ags_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'cur_id', 'cur_id');
    }
}
