<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gsa extends Model
{
    use SoftDeletes;

    protected $table = 'gsas';
    protected $primaryKey = 'ags_id';
    protected $fillable = [
        'ala_id',
        'niv_id',
        'gra_id',
        'sec_id',
        'is_deleted'
    ];

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'ala_id', 'ala_id');
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
        return $this->belongsTo(Seccion::class, 'sec_id', 'sec_id');
    }

    public function matricula()
    {
        return $this->hasMany(Matricula::class, 'ags_id', 'ags_id');
    }
    public function nota()
    {
        return $this->hasMany(Nota::class, 'ags_id', 'ags_id');
    }
}
