<?php

namespace App\Http\Controllers;

use App\Models\AsignarCurso;
use App\Models\Curso;
use App\Models\Nivel;
use App\Models\Persona;
use App\Models\PersonalAcademico;
use Illuminate\Http\Request;

use function Illuminate\Log\log;
class AsignarCursoController extends Controller
{
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
    public function show(AsignarCurso $asignarCurso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AsignarCurso $asignarCurso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AsignarCurso $asignarCurso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AsignarCurso $asignarCurso)
    {
        //
    }

    public function inicio(Request $request ,$nivel = null)
    {
        $curso = null;
        $docente = null;
        $nivel = Nivel::all();
        if($request){

            $cursos = Curso::selectRaw('cur_nombre, cur_abreviatura')->where('cur_horas', '>', 0)->where('niv_id', $request->niv_id)->where('is_deleted', '!=', 1)->groupBy('cur_nombre', 'cur_abreviatura')->get();
            $docentes = PersonalAcademico::whereIn('rol_id', [4])->where('niv_id', $request->niv_id)->where('is_deleted', '!=', 1)->get();
            foreach ($docentes as $d) {
                $asignaciones = AsignarCurso::where('pa_id', $d->pa_id)
                    ->where('asig_is_deleted', '!=', 1)
                    ->pluck('curso')
                    ->toArray();
                if ($asignaciones) {
                    $d->checked = $asignaciones;
                } else {
                    $d->checked = [];
                }

                $d->dni = $d->persona->per_dni;
                $d->nombres = $d->persona->per_nombres;
                $d->apellidos = $d->persona->per_apellidos;
            }
        }
        return view('view.asignarCurso.inicio' , compact('nivel','cursos','docentes'));
    }

    public function asignarCurso(Request $request)
    {
        try {
            log($request);
        } catch (\Throwable $th) {
            //throw $th;
            log($th);
        }


        // $asignarCurso = new AsignarCurso();
        // $asignarCurso->pa_id = $request->pa_id;
        // $asignarCurso->curso = $request->curso;
        // $asignarCurso->asig_is_deleted = 0;
        // $asignarCurso->save();
        // return response()->json(['success' => 'Curso asignado correctamente']);
    }

    public function eliminarCurso(Request $request)
    {
        try {
            log($request);
        } catch (\Throwable $th) {
            //throw $th;
            log($th);
        }
        // $asignarCurso = AsignarCurso::where('pa_id', $request->pa_id)->where('curso', $request->curso)->first();
        // $asignarCurso->asig_is_deleted = 1;
        // $asignarCurso->save();
        // return response()->json(['success' => 'Curso eliminado correctamente']);
    }

    public function asignacionMasivaCurso(Request $request)
    {
        try {
            log($request);
        } catch (\Throwable $th) {
            //throw $th;
            log($th);
        }



        // $docente = PersonalAcademico::find($request->pa_id);
        // $asignaciones = AsignarCurso::where('pa_id', $request->pa_id)->where('asig_is_deleted', '!=', 1)->pluck('curso')->toArray();
        // $cursos = Curso::where('niv_id', $docente->niv_id)->where('is_deleted', '!=', 1)->get();
        // foreach ($cursos as $curso) {
        //     if (!in_array($curso->cur_abreviatura, $asignaciones)) {
        //         $asignarCurso = new AsignarCurso();
        //         $asignarCurso->pa_id = $request->pa_id;
        //         $asignarCurso->curso = $curso->cur_abreviatura;
        //         $asignarCurso->asig_is_deleted = 0;
        //         $asignarCurso->save();
        //     }
        // }
        // return response()->json(['success' => 'Cursos asignados correctamente']);
    }

    public function eliminacionMasivaCurso(Request $request)
    {
        try {
            log($request);
        } catch (\Throwable $th) {
            //throw $th;
            log($th);
        }



        // $docente = PersonalAcademico::find($request->pa_id);
        // $asignaciones = AsignarCurso::where('pa_id', $request->pa_id)->where('asig_is_deleted', '!=', 1)->pluck('curso')->toArray();
        // $cursos = Curso::where('niv_id', $docente->niv_id)->where('is_deleted', '!=', 1)->get();
        // foreach ($cursos as $curso) {
        //     if (in_array($curso->cur_abreviatura, $asignaciones)) {
        //         $asignarCurso = AsignarCurso::where('pa_id', $request->pa_id)->where('curso', $curso->cur_abreviatura)->first();
        //         $asignarCurso->asig_is_deleted = 1;
        //         $asignarCurso->save();
        //     }
        // }
        // return response()->json(['success' => 'Cursos eliminados correctamente']);
    }
}
