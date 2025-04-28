<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsignarGrado extends Model
{
    use SoftDeletes;

    protected $table = 'asignar_grados';
    protected $primaryKey = 'asig_id';
    protected $fillable = [
        'pa_id',
        'niv_id',
        'gra_id',
        'seccion',
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

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'gra_id', 'gra_id');
    }
    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'seccion', 'sec_descripcion');
    }
}
