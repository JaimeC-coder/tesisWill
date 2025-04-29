<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

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

    //relacion con apoderado
    public function apoderado()
    {
        return $this->hasOne(Apoderado::class, 'per_id', 'per_id');
    }

    //relacion con distrito
    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'per_distrito', 'idDist');
    }
    //relacion con provincia
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'per_provincia', 'idProv');
    }
    //relacion con departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'per_departamento', 'idDepa');
    }
    //relacion con personal academico
    public function personalAcademico()
    {
        return $this->hasOne(PersonalAcademico::class, 'per_id', 'per_id');
    }

    //metodo para sexo
    public function getPerSexoAttribute($value)
    {

        return $value == 'M' ? 'Masculino' : 'Femenino';
    }

    //metodo para estado civil

    public function getPerEstadoCivilAttribute($value)
    {

        if ($value == null) {
            return 'No definido';
        } elseif ($value == 'S') {
            return 'Soltero';
        } elseif ($value == 'C') {
            return 'Casado';
        } elseif ($value == 'D') {
            return 'Divorciado';
        } elseif ($value == 'V') {
            return 'Viudo';
        }
    }

    public function setPerSexoAttribute($value)
    {
        $this->attributes['per_sexo'] = $value == 'Masculino' ? 'M' : 'F';
    }
    public function setPerEstadoCivilAttribute($value)
    {
        if ($value == 'Soltero') {
            $this->attributes['per_estado_civil'] = 'S';
        } elseif ($value == 'Casado') {
            $this->attributes['per_estado_civil'] = 'C';
        } elseif ($value == 'Divorciado') {
            $this->attributes['per_estado_civil'] = 'D';
        } elseif ($value == 'Viudo') {
            $this->attributes['per_estado_civil'] = 'V';
        }else {
            $this->attributes['per_estado_civil'] = 'S';
        }
    }
}
