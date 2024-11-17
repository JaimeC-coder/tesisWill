<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rol extends Model
{

    use SoftDeletes;

    protected $table = 'rols';
    protected $primaryKey = 'rol_id';
    protected $fillable = [
        'rol_descripcion',
        'rol_estado',
        'is_deleted'
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'rol_id', 'rol_id');
    }

    public function personalAcademico()
    {
        return $this->hasMany(PersonalAcademico::class, 'rol_id', 'rol_id');
    }
}
