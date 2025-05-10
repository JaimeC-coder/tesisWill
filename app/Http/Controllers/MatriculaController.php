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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatriculaController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function inicio()
    {

        $informacion = Matricula::where('is_deleted', '!=', 1)->get();
        foreach ($informacion as $value => $m) {
            $id = $value + 1;
            $alumno = Alumno::where('alu_id', $m->alu_id)->first();
            $persona = Persona::where('per_id', $alumno->per_id)->first();
            $apoderado = Apoderado::where('apo_id', $alumno->apo_id)->first();
            $apo_persona = Persona::where('per_id', $apoderado->per_id)->first();
            $periodo = Periodo::where('per_id', $m->per_id)->first();
            $anio = Anio::where('anio_id', $periodo->anio_id)->where('anio_estado', '!=', 0)->first();
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


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $matricula = new Matricula();
        $periodos = Periodo::where('per_estado', 1)->where('is_deleted',0)->get();
        if ($periodos == null) {
            return redirect()->route('matricula.inicio')->with('error', 'No hay periodo activo');
        }
        $niveles = Nivel::get();
        return view('view.matricula.create', compact('matricula', 'niveles', 'periodos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        DB::beginTransaction();
        try {
            $gsa = Gsa::create([
                'ala_id' => $request->ala_id,
                'niv_id' => $request->niv_id,
                'gra_id' => $request->gra_id,
                'sec_id' => $request->sec_id
            ]);

            Matricula::create([
                'per_id' => $request->per_id,
                'alu_id' => $request->alu_id,
                'ags_id' => $gsa->ags_id,
                'mat_fecha' => $request->fechamatricula,
                'mat_situacion' => $request->situacion,
                'mat_tipo_procedencia' => $request->tipo_procedencia,
                'mat_colegio_procedencia' => $request->colegio_procedencia,
                'mat_observacion' => $request->observacion
            ]);

            Seccion::where('sec_id', $request->sec_id)->decrement('sec_vacantes', 1);

            DB::commit();

            return redirect()->route('matricula.inicio')->with('success', 'Matricula creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('matricula.inicio')->with('success', 'Tuvimos un problema, inténtelo nuevamente');
        }
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
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Matricula $matricula)
    {
        //
    }




    public function showGrados(Request $request)
    {

        Log::info($request);
        $nivel = $request->niv_id;
        $alumno = $request->alu_id;
        //si es que el alumno existe debuelveme los grados que tiene

        if ($alumno != null) {
            $matricula  = DB::table('matriculas as ma')
                ->join('gsas as gsa', 'ma.ags_id', '=', 'gsa.ags_id')
                ->join('grados as gr', 'gsa.gra_id', '=', 'gr.gra_id')
                ->where('ma.alu_id', $alumno)
                ->pluck('gr.gra_id');

            // $descripcion = $matricula?->gsa?->grado?->gra_id;
            $gradosRestantes = Grado::where('niv_id', $nivel)
                ->whereNotIn('gra_id', $matricula)
                ->get();
            return response()->json($gradosRestantes);
        }

        $grados = Grado::where('niv_id', $nivel)->get();
        return response()->json($grados);
    }

    public function showSecciones(Request $request)
    {
        $grado = $request->gra_id;
        $secciones = Seccion::where('gra_id', $grado)->get();
        return response()->json($secciones);
    }

    public function infoSecciones(Request $request)
    {
        $data = $request->seccion;
        $seccion = Seccion::where('sec_id', $data)->first();
        $aula = Aula::where('ala_id', $seccion->sec_aula)->first();
        $seccion->aula = $aula->ala_descripcion;
        $seccion->ala_id = $aula->ala_id;
        return response()->json($seccion);

    }


    public function searchAlumno(Request $request)
    {

        $nrodoc = $request->dni;
        $persona = Persona::where('per_dni', $nrodoc)->first();

        $peridos = Periodo::where('per_estado', 1)->orderBy('per_id', 'desc')->first();
        Log::info($peridos);
        if ($persona) {

            $alumno = Alumno::where('per_id', $persona->per_id)->first();

            if ($alumno) {
                $matricula = Matricula::where('alu_id', $persona->alumno->alu_id)
                    ->where('per_id', $peridos->per_id)
                    ->first();

                if ($matricula) {
                    return  response()->json([
                        'status' => 200,
                        'alumno' => 1,
                        'data' => 'El alumno ya se encuentra matriculado'
                    ]);
                } else {
                    $apoderado = Apoderado::where('apo_id', $alumno->apo_id)->first();
                    $apo_persona = Persona::where('per_id', $apoderado->per_id)->first();
                    if ($apo_persona->per_nombres == "") {
                        $persona->apo_nombre_completo = $apo_persona->per_nombre_completo;
                    } else {
                        $persona->apo_nombre_completo = $apo_persona->per_apellidos . ' ' . $apo_persona->per_nombres;
                    }
                    /* $persona->apo_nombres = $apo_persona->per_nombres;
                    $persona->apo_apellidos = $apo_persona->per_apellidos; */
                    $persona->apo_parentesco = $apoderado->apo_parentesco;
                    $persona->apo_vive_con_estudiante = $apoderado->apo_vive_con_estudiante;
                    $persona->alu_id = $alumno->alu_id;
                    $persona->alu_estado = $alumno->alu_estado;
                    $persona->apo_id = $alumno->apo_id;
                }
            } else {

                return  response()->json([
                    'status' => 200,
                    'alumno' => 1,
                    'data' => 'El alumno no se encuentra registrado'
                ]);
            }
        } else {

            return  response()->json([
                'status' => 200,
                'alumno' => 1,
                'data' => 'La persona no se encuentra registrado'
            ]);
        }

        return  response()->json([
            'status' => 200,
            'alumno' => 0,
            'data' => $persona
        ]);
    }
}
