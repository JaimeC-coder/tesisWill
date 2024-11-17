<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotaCapacidad extends Model
{
    use SoftDeletes;


    protected $table = 'nota_capacidads';
    protected $primaryKey = 'nc_id';
    protected $fillable = [
        'nc_descripcion',
        'nc_nota',
        'nt_id',
        'nc_is_deleted'
    ];

    public function nota()
    {
        return $this->belongsTo(Nota::class, 'nt_id', 'nt_id');
    }
}
