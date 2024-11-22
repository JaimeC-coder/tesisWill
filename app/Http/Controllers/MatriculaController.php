<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Anio;
use App\Models\Apoderado;
use App\Models\Aula;
use App\Models\Grado;
use App\Models\Gsa;
use App\Models\Matricula;
use App\Models\Nivel;
use App\Models\Periodo;
use App\Models\Persona;
use App\Models\Seccion;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Matricula $matricula)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Matricula $matricula)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Matricula $matricula)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Matricula $matricula)
    {
        //
    }
    public function inicio(){

        $informacion = Matricula::where('is_deleted', '!=', 1)->get();
        foreach ($informacion as $value => $m) {
            $id =$value+1;
            $alumno = Alumno::where('alu_id', $m->alu_id)->first();
            $persona = Persona::where('per_id', $alumno->per_id)->first();
            $apoderado = Apoderado::where('apo_id', $alumno->apo_id)->first();
            $apo_persona = Persona::where('per_id', $apoderado->per_id)->first();
            $periodo = Periodo::where('per_id', $m->per_id)->first();
            $anio = Anio::where('anio_id', $periodo->anio_id)->first();
            $gsa = Gsa::where('ags_id', $m->ags_id)->first();
            $aula = Aula::where('ala_id', $gsa->ala_id)->first();
            $nivel = Nivel::where('niv_id', $gsa->niv_id)->first();
            $grado = Grado::where('gra_id', $gsa->gra_id)->first();
            $seccion = Seccion::where('sec_id', $gsa->sec_id)->first();
            $m->id_persona = $persona->per_id;
            $m->dni = $persona->per_dni;
            $m->alumno = $persona->per_apellidos . ' ' . $persona->per_nombres;
            if ($apo_persona->per_nombres == "") {
                $m->apoderado = $apo_persona->per_nombre_completo;
            } else {
                $m->apoderado = $apo_persona->per_apellidos . ' ' . $apo_persona->per_nombres;
            }
            $m->parentesco = $apoderado->apo_parentesco;
            $m->periodo = $anio->anio_descripcion;
            $m->estadoPeriodo = $periodo->per_estado;
            $m->aula = $aula->ala_descripcion;
            $m->nivel = $nivel->niv_descripcion;
            $m->grado = $grado->gra_descripcion;
            $m->seccion = $seccion->sec_descripcion;
        }
        return view('view.matricula.inicio', compact('informacion'));
    }
}
