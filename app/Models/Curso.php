<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model
{

    use SoftDeletes;

    /**
     *   Schema::create('cursos', function (Blueprint $table) {
            $table->bigIncrements('cur_id');
            $table->string('cur_nombre', 50)->default('0');
            $table->string('cur_abreviatura', 50)->default('0');
            $table->integer('cur_horas')->default(0);
            $table->unsignedBigInteger('gra_id')->nullable();
            $table->unsignedBigInteger('niv_id')->nullable();
            $table->unsignedBigInteger('per_id')->nullable();
            $table->string('cur_estado', 50)->default('1')->comment('1: Activo; 2: Inactivo');
            $table->char('is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('gra_id')->references('gra_id')->on('grados');
            $table->foreign('niv_id')->references('niv_id')->on('nivels');
            $table->foreign('per_id')->references('per_id')->on('periodos');
            $table->timestamps();
            $table->softDeletes();
        });
     */

    protected $table = 'cursos';
    protected $primaryKey = 'cur_id';
    protected $fillable = [
        'cur_nombre',
        'cur_abreviatura',
        'cur_horas',
        'gra_id',
        'niv_id',
        'per_id',
        'cur_estado',
        'is_deleted'
    ];

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'gra_id', 'gra_id');
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'niv_id', 'niv_id');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'per_id', 'per_id');
    }

    public function capacidad()
    {
        return $this->hasMany(Capacidad::class, 'cur_id', 'cur_id');
    }
}
