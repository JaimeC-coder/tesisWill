<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institucion extends Model
{
    use SoftDeletes;



    protected $table = 'institucions';
    protected $primaryKey = 'ie_id';

    protected $fillable = [
        'ie_codigo_modular',
        'ie_anexo',
        'ie_nivel',
        'ie_nombre',
        'ie_gestion',
        'ie_departamento',
        'ie_provincia',
        'ie_distrito',
        'ie_direccion',
        'ie_dre',
        'ie_ugel',
        'ie_genero',
        'ie_turno',
        'ie_dias_laborales',
        'ie_director',
        'ie_telefono',
        'ie_email',
        'ie_web',
        'is_deleted'
    ];

    //relacion con departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'ie_departamento', 'idDepa');
    }
    //relacion con provincia
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'ie_provincia', 'idProv');
    }
    //relacion con distrito
    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'ie_distrito', 'idDist');
    }
    //relacion con dre
}
