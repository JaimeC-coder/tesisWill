<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalAcademico extends Model
{
    use SoftDeletes;



    protected $table = 'personal_academicos';
    protected $primaryKey = 'pa_id';
    protected $fillable = [
        'per_id',
        'pa_turno',
        'pa_condicion_laboral',
        'niv_id',
        'pa_especialidad',
        'rol_id',
        'pa_is_tutor',
        'is_deleted'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'per_id', 'per_id');
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'niv_id', 'niv_id');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'rol_id');
    }

    public function secciones()
    {
        return $this->hasMany(Seccion::class, 'sec_tutor', 'pa_id');
    }

    public function asignarCursos()
    {
        return $this->hasMany(AsignarCurso::class, 'pa_id', 'pa_id');
    }
    public function setPaTurnoAttribute($value)
    {
        $this->attributes['pa_turno'] = strtoupper($value);
    }

    public function setPaCondicionLaboralAttribute($value)
    {
        $this->attributes['pa_condicion_laboral'] = strtoupper($value);
    }

    public function setPaEspecialidadAttribute($value)
    {
        $this->attributes['pa_especialidad'] = strtoupper($value);
    }
   
}
