<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anio extends Model
{
    use SoftDeletes;




    protected $table = 'anios';
    protected $primaryKey = 'anio_id';
    protected $fillable = [
        'anio_descripcion',
        'anio_fechaInicio',
        'anio_fechaFin',
        'anio_duracionHoraAcademica',
        'anio_duracionHoraLibre',
        'anio_cantidadPersonal',
        'anio_tallerSeleccionable',
        'anio_estado',
        'is_deleted'
    ];
}
