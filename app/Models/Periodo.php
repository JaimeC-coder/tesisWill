<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Periodo extends Model
{
    use SoftDeletes;

    /**
     *   Schema::create('periodos', function (Blueprint $table) {
            $table->bigIncrements('per_id');
            $table->unsignedBigInteger('anio_id')->nullable();
            $table->date('per_inicio_matriculas')->nullable();
            $table->date('per_final_matricula')->nullable();
            $table->date('per_limite_cierre')->nullable();
            $table->integer('per_tp_notas')->nullable();
            $table->char('per_estado', 1)->default('0')->comment('1: Aperturado; 2:Finalizado');
            $table->char('is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('anio_id')->references('anio_id')->on('anios');
            $table->timestamps();
            $table->softDeletes();
        });
     */

    protected $table = 'periodos';
    protected $primaryKey = 'per_id';
    protected $fillable = [
        'anio_id',
        'per_inicio_matriculas',
        'per_final_matricula',
        'per_limite_cierre',
        'per_tp_notas',
        'per_estado',
        'is_deleted'
    ];

    public function anio()
    {
        return $this->belongsTo(Anio::class, 'anio_id', 'anio_id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'per_id', 'per_id');
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'per_id', 'per_id');
    }
}
