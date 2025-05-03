<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsignarCurso extends Model
{
    use SoftDeletes;

    protected $table = 'asignar_cursos';
    protected $primaryKey = 'asig_id';
    protected $fillable = [
        'pa_id',
        'niv_id',
        'curso',
        'asig_is_deleted'
    ];

    public function personalAcademico()
    {
        return $this->belongsTo(PersonalAcademico::class, 'pa_id', 'pa_id');
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'niv_id', 'niv_id');
    }

    public function setCursoAttribute($value)
    {
        $this->attributes['curso'] = strtoupper($value);
    }
}
