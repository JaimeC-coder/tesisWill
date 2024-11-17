<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matricula extends Model
{
    use SoftDeletes;

    protected $table = 'matriculas';
    protected $primaryKey = 'mat_id';

    protected $fillable = [
        'per_id',
        'alu_id',
        'ags_id',
        'mat_fecha',
        'mat_situacion',
        'mat_tipo_procedencia',
        'mat_colegio_procedencia',
        'mat_observacion',
        'mat_estado',
        'is_deleted'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'per_id', 'per_id');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alu_id', 'alu_id');
    }

    public function gsa()
    {
        return $this->belongsTo(Gsa::class, 'ags_id', 'ags_id');
    }
}
