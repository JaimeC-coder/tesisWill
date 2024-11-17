<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalAcademico extends Model
{
    use SoftDeletes;

    /**
     *   Schema::create('personal_academicos', function (Blueprint $table) {
            $table->bigIncrements('pa_id');
            $table->unsignedBigInteger('per_id')->nullable();
            $table->string('pa_turno', 255)->nullable();
            $table->string('pa_condicion_laboral', 255)->nullable();
            $table->unsignedBigInteger('niv_id')->nullable();
            $table->string('pa_especialidad', 255)->nullable();
            $table->unsignedBigInteger('rol_id')->nullable();
            $table->char('pa_is_tutor', 1)->default('0')->comment('1: Si es tutor; 2: No es tutor');
            $table->char('is_deleted', 1)->default('0')->comment('1: Eliminado; 0:No Eliminado');
            $table->foreign('per_id')->references('per_id')->on('personas');
            $table->foreign('niv_id')->references('niv_id')->on('nivels');
            $table->foreign('rol_id')->references('rol_id')->on('rols');
            $table->timestamps();
            $table->softDeletes();
        });
     */

    protected $table = 'personal_academicos';
    protected $primaryKey = 'pa_id';
    protected $fillable = [
        'per_id',
        'pa_turno',
        'pa_condicion_laboral',
        'niv_id',
        'pa_especialidad',
        'rol_id',
        'pa_is_tutor',
        'is_deleted'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'per_id', 'per_id');
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'niv_id', 'niv_id');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'rol_id');
    }

    public function secciones()
    {
        return $this->hasMany(Seccion::class, 'sec_tutor', 'pa_id');
    }

    public function asignarCursos()
    {
        return $this->hasMany(AsignarCurso::class, 'pa_id', 'pa_id');
    }
}
