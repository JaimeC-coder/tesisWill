<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aula extends Model
{
    use SoftDeletes;

    protected $table = 'aulas';
    protected $primaryKey = 'ala_id';
    protected $fillable = [
        'ala_descripcion',
        'ala_tipo',
        'ala_aforo',
        'ala_ubicacion',
        'ala_estado',
        'ala_is_delete',
        'ala_en_uso'
    ];

    public function horario()
    {
        return $this->hasMany(Horario::class, 'ala_id', 'ala_id');
    }

    public function seccion()
    {
        return $this->hasMany(Seccion::class, 'sec_aula', 'ala_id');
    }

}
