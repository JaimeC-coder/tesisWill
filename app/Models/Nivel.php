<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nivel extends Model
{
    use SoftDeletes;


    protected $table = 'nivels';
    protected $primaryKey = 'niv_id';
    protected $fillable = [
        'niv_descripcion'
    ];

    public function grado()
    {
        return $this->hasMany(Grado::class, 'niv_id', 'niv_id');
    }

    public function personalAcademico()
    {
        return $this->hasMany(PersonalAcademico::class, 'niv_id', 'niv_id');
    }

    public function seccion()
    {
        return $this->hasMany(Seccion::class, 'niv_id', 'niv_id');
    }


    //! no se si existen aun
    public function alumno()
    {
        return $this->hasMany(Alumno::class, 'niv_id', 'niv_id');
    }

    public function matricula()
    {
        return $this->hasMany(Matricula::class, 'niv_id', 'niv_id');
    }

    public function aula()
    {
        return $this->hasMany(Aula::class, 'niv_id', 'niv_id');
    }

    public function horario()
    {
        return $this->hasMany(Horario::class, 'niv_id', 'niv_id');
    }

    public function apoderado()
    {
        return $this->hasMany(Apoderado::class, 'niv_id', 'niv_id');
    }

    public function persona()
    {
        return $this->hasMany(Persona::class, 'niv_id', 'niv_id');
    }

    public function periodo()
    {
        return $this->hasMany(Periodo::class, 'niv_id', 'niv_id');
    }

    //* estos si son

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'niv_id', 'niv_id');
    }

    public function gsa()
    {
        return $this->hasMany(Gsa::class, 'niv_id', 'niv_id');
    }

}
