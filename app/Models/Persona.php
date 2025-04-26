<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use SoftDeletes;

    protected $table = 'personas';
    protected $primaryKey = 'per_id';
    protected $fillable = [
        'per_dni',
        'per_nombres',
        'per_apellidos',
        'per_nombre_completo',
        'per_email',
        'per_sexo',
        'per_fecha_nacimiento',
        'per_estado_civil',
        'per_celular',
        'per_pais',
        'per_departamento',
        'per_provincia',
        'per_distrito',
        'per_direccion',
        'is_deleted'
    ];

    public function alumno()
    {
        return $this->hasOne(Alumno::class, 'per_id', 'per_id');
    }

    public function usuario()
    {
        return $this->hasOne(User::class, 'per_id', 'per_id');
    }



}
