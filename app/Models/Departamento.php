<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departamento extends Model
{
    use SoftDeletes;


    protected $table = 'departamentos';
    protected $primaryKey = 'idDepa';
    protected $fillable = [
        'departamento'

    ];
    public function provincia()
    {
        return $this->hasMany(Provincia::class, 'idDepa', 'idDepa');
    }



}
