<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tipo extends Model
{
    use SoftDeletes;

    protected $table = 'tipos';
    protected $primaryKey = 'tp_id';
    protected $fillable = [
        'tp_tipo',
        'is_deleted'
    ];


       public function setTpTipoAttribute($value)
    {
        $this->attributes['tp_tipo'] = strtoupper($value);
    }

}
