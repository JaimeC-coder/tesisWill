<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distrito extends Model
{
    use SoftDeletes;

 
    protected $table = 'distritos';
    protected $primaryKey = 'idDist';
    protected $fillable = [
        'distrito',
        'idProv'
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'idProv', 'idProv');
    }

}
