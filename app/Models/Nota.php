<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nota extends Model
{
    use SoftDeletes;


    protected $table = 'notas';
    protected $primaryKey = 'nt_id';

    protected $fillable = [
        'per_id',
        'alu_id',
        'pa_id',
        'ags_id',
        'curso_id',
        'nt_bimestre',
        'nt_nota',
        'nt_is_deleted'
    ];

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'per_id', 'per_id');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alu_id', 'alu_id');
    }

    public function personalAcademico()
    {
        return $this->belongsTo(PersonalAcademico::class, 'pa_id', 'pa_id');
    }

    public function gsa()
    {
        return $this->belongsTo(Gsa::class, 'ags_id', 'ags_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id', 'cur_id');
    }
    
}
