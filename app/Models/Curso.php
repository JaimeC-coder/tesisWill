<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model
{

    use SoftDeletes;

    protected $table = 'cursos';
    protected $primaryKey = 'cur_id';
    protected $fillable = [
        'cur_nombre',
        'cur_abreviatura',
        'cur_horas',
        'gra_id',
        'niv_id',
        'per_id',
        'cur_estado',
        'is_deleted'
    ];

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'gra_id', 'gra_id');
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'niv_id', 'niv_id');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'per_id', 'per_id');
    }

    public function capacidad()
    {
        return $this->hasMany(Capacidad::class, 'cur_id', 'cur_id')->where('cap_is_deleted', '!=', 1) // Filtrar registros
        ->select('cap_id', 'cap_descripcion', 'cur_id'); // Seleccionar solo los campos necesarios;
    }
}
