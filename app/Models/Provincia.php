<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provincia extends Model
{
    use SoftDeletes;

    protected $table = 'provincias';
    protected $primaryKey = 'idProv';
    protected $fillable = [
        'provincia',
        'idDepa'
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'idDepa', 'idDepa');
    }

    public function distrito()
    {
        return $this->hasMany(Distrito::class, 'idProv', 'idProv');
    }
}
