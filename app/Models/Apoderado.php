<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apoderado extends Model
{
    use SoftDeletes;

    protected $table = 'apoderados';
    protected $primaryKey = 'apo_id';
    protected $fillable = [
        'per_id',
        'apo_parentesco',
        'apo_vive_con_estudiante',
        'is_deleted'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'per_id', 'per_id');
    }

}
