<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Periodo extends Model
{
    use SoftDeletes;

   

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

    public function Tipo()
    {
        return $this->belongsTo(Tipo::class, 'per_tp_notas', 'tp_id');
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'per_id', 'per_id');
    }
}
