<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsignarCurso extends Model
{
    use SoftDeletes;

    /**
     *  Schema::create('asignar_cursos', function (Blueprint $table) {

            $table->bigIncrements('asig_id');
            $table->unsignedBigInteger('pa_id')->nullable();
            $table->unsignedBigInteger('niv_id')->nullable();
            $table->string('curso', 255)->nullable();
            $table->char('asig_is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('pa_id')->references('pa_id')->on('personal_academicos');
            $table->foreign('niv_id')->references('niv_id')->on('nivels');
            $table->softDeletes();
            $table->timestamps();
        });
     */

    protected $table = 'asignar_cursos';
    protected $primaryKey = 'asig_id';
    protected $fillable = [
        'pa_id',
        'niv_id',
        'curso',
        'asig_is_deleted'
    ];

    public function personalAcademico()
    {
        return $this->belongsTo(PersonalAcademico::class, 'pa_id', 'pa_id');
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'niv_id', 'niv_id');
    }
    
}
