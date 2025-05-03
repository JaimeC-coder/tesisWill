<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Horario extends Model
{
    use SoftDeletes;
   
    protected $table = 'horarios';
    protected $primaryKey = 'hor_id';
    protected $fillable = [
        'per_id',
        'ags_id',
        'cur_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'color',
        'editable',
        'is_deleted'
    ];


    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'per_id', 'per_id');
    }

    public function gsa()
    {
        return $this->belongsTo(Gsa::class, 'ags_id', 'ags_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'cur_id', 'cur_id');
    }
}
