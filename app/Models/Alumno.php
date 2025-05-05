<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alumno extends Model
{
    use SoftDeletes;

    protected $table = 'alumnos';
    protected $primaryKey = 'alu_id';
    protected $fillable = [
        'per_id',
        'apo_id',
        'name_libreta_notas',
        'name_ficha_matricula',
        'alu_estado',
        'is_deleted'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'per_id', 'per_id');
    }

    public function apoderado()
    {
        return $this->belongsTo(Apoderado::class, 'apo_id', 'apo_id');
    }

    public function matricula()
    {
        return $this->hasMany(Matricula::class, 'alu_id', 'alu_id');
    }



    public function ultimaMatricula()
    {
        return $this->hasOne(Matricula::class, 'alu_id')->latest(); // Ordenado por created_at
        // O usa ->latest('año') si quieres por año u otro campo
    }
}
