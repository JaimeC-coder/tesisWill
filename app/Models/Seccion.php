<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seccion extends Model
{
    use SoftDeletes;


    protected $table = 'seccions';
    protected $primaryKey = 'sec_id';
    protected $fillable = [
        'sec_descripcion',
        'sec_tutor',
        'sec_aula',
        'gra_id',
        'sec_periodo',
        'sec_vacantes',
        'sec_is_delete'
    ];

    public function tutor()
    {
        return $this->belongsTo(PersonalAcademico::class, 'sec_tutor', 'pa_id');
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'sec_aula', 'ala_id');
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'gra_id', 'gra_id');
    }

    public function gsa()
    {
        return $this->hasMany(Gsa::class, 'sec_id', 'sec_id');
    }
}
