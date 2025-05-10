<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grado extends Model
{
    use SoftDeletes;

    protected $table = 'grados';
    protected $primaryKey = 'gra_id';
    protected $fillable = [
        'gra_descripcion',
        'niv_id',
        'gra_estado',
        'gra_is_delete'
    ];

    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'niv_id', 'niv_id');
    }

    public function seccion()
    {
        return $this->hasMany(Seccion::class, 'gra_id', 'gra_id');
    }


    public function gsa()
    {
        return $this->hasMany(Gsa::class, 'gra_id', 'gra_id');
    }

    public function curso()
    {
        return $this->hasMany(Curso::class, 'gra_id', 'gra_id');
    }

    public function asignarGrado()
    {
        return $this->hasMany(AsignarGrado::class, 'gra_id', 'gra_id');
    }

    public function setGraDescripcionAttribute($value)
    {
        $this->attributes['gra_descripcion'] = strtoupper($value);
    }
}
