<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Capacidad extends Model
{

    use SoftDeletes;

    protected $table = 'capacidads';

    protected $primaryKey = 'cap_id';

    protected $fillable = [
        'cap_descripcion',
        'cur_id',
        'cap_is_deleted'
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'cur_id', 'cur_id');
    }

    public function getResultadoAttribute()
    {
        return $this->cap_descripcion;
    }
}
