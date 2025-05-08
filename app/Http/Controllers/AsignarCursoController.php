<?php

namespace App\Http\Controllers;

use App\Models\AsignarCurso;
use App\Models\Curso;
use App\Models\Nivel;
use App\Models\Persona;
use App\Models\PersonalAcademico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function inicio(Request $request, $nivel = null)
    {
        $curso = null;
        $docente = null;
        $nivel = Nivel::all();
        if ($request) {

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
        return view('view.asignarCurso.inicio', compact('nivel', 'cursos', 'docentes'));
    }




    public function asignacionMasivaCurso(Request $request)
    {
        try {

            DB::beginTransaction();
            $datos_asignar = $request['cursos'];
            $docente=$request['docente'];
            $niv_id=$request['niv_id'];
            $cursos_asignados = AsignarCurso::where('pa_id', $docente)->where('asig_is_deleted', '!=', 1)
            ->where('niv_id', $niv_id)->get();
            foreach ($cursos_asignados as $curso) {
                $curso->asig_is_deleted = 1;
                $curso->save();
            }
            foreach ($datos_asignar as $value) {
                $data = AsignarCurso::where('curso', $value)
                ->where('pa_id', $docente)
                ->where('asig_is_deleted', '!=', 1)
                ->first();
                if (!$data) {
                    AsignarCurso::create([
                        'pa_id' => $docente,
                        'niv_id' => $niv_id,
                        'curso' => $value
                    ]);
                } else {
                    $data->asig_is_deleted = 0;
                    $data->save();
                }
            }
            DB::commit();
            return response()->json([
                'status' => 1
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 0
            ]);
            log($th);
        }

    }

    public function eliminacionMasivaCurso(Request $request)
    {
        try {
            DB::beginTransaction();
            $docente=$request['docente'];
            $niv_id=$request['niv_id'];
            $cursos_asignados = AsignarCurso::where('pa_id', $docente)->where('asig_is_deleted', '!=', 1)
            ->where('niv_id', $niv_id)->get();
            foreach ($cursos_asignados as $curso) {
                $curso->asig_is_deleted = 1;
                $curso->save();
            }

            DB::commit();
            return response()->json([
                'status' => 1
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 0
            ]);
            log($th);
        }

    }
}
