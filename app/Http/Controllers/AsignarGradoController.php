<?php

namespace App\Http\Controllers;

use App\Models\AsignarCurso;
use App\Models\AsignarGrado;
use App\Models\Curso;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\Persona;
use App\Models\PersonalAcademico;
use App\Models\Seccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class AsignarGradoController extends Controller
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
    public function show(AsignarGrado $asignarGrado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AsignarGrado $asignarGrado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AsignarGrado $asignarGrado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AsignarGrado $asignarGrado)
    {
        //
    }
    public function inicio(Request $request, $nivels = null)
    {

        $grados = null;
        $asignacion = null;
        $nivels = Nivel::all();
        $curso = $request->cursoId;
        $nivel = $request->niv_id;

        if ($curso == "-1") {
            $asignacion_curso = AsignarCurso::where('niv_id', $nivel)->where('asig_is_deleted', '!=', 1)->get();
        } else {
            $asignacion_curso = AsignarCurso::where('curso', $curso)->where('niv_id', $nivel)->where('asig_is_deleted', '!=', 1)->get();
        }
        foreach ($asignacion_curso as $d) {
            $docentes = PersonalAcademico::where('pa_id', $d->pa_id)->where('is_deleted', '!=', 1)->first();
            $persona = Persona::where('per_id', $docentes->per_id)->first();
            $asignaciones = AsignarGrado::where('pa_id', $d->pa_id)->where('asig_is_deleted', '!=', 1)->get();
            $array2 = [];
            foreach ($asignaciones as $value) {
                array_push($array2, $value->gra_id . '>' . $value->seccion);
            }
            $d->totalAsignaciones = count($array2);
            $d->asignaciones = $array2;
            $d->dni = $persona->per_dni;
            $d->nombres = $persona->per_nombres;
            $d->apellidos = $persona->per_apellidos;
        }

        $new_grados = [];
        $new_secciones = [];

        $data_grados = Grado::where('niv_id', $nivel)->where('gra_is_delete', '!=', 1)->get();
        foreach ($data_grados as $g) {
            $secciones = Seccion::where('gra_id', $g->gra_id)->where('sec_is_delete', '!=', 1)->get();
            $seccion = [];
            $grado = [];
            foreach ($secciones as $s) {
                $value = $g->gra_id . '>' . $s->sec_descripcion;
                array_push($grado, $value);
                array_push($seccion, $s->sec_descripcion);
            }
            array_push($new_grados, $grado);
            array_push($new_secciones, $seccion);
        }
        foreach ($asignacion_curso as $d) {
            $resultado = [];
            $d->secciones = $new_secciones;
            $d->grados = $new_grados;
            if ($d->totalAsignaciones > 0) {
                foreach ($d->asignaciones as $value) {
                    $grados =  $d->grados;
                    foreach ($grados as $ke => $va) {
                        foreach ($va as $k => $v) {
                            if ($v == $value) {
                                $valor = $ke . '-' . $k;
                                array_push($resultado, $valor);
                            }
                        }
                    }
                }
                $d->grados = $grados;
            }
        }

        return view('view.asignarGrado.inicio', compact('nivels', 'data_grados', 'asignacion_curso'));
    }


    public function listCurso(Request $request)
    {
        $nivel = $request->nivel_id;
        $cursos = Curso::selectRaw('cur_nombre, cur_abreviatura')->where('cur_horas', '>', 0)->where('niv_id', $nivel)->where('is_deleted', '!=', 1)->groupBy('cur_nombre', 'cur_abreviatura')->get();

        return response()->json([
            'status' => 200,

            'cursos' => $cursos,

        ]);
    }

    public function masiva(Request $request)
    {

        $datos_asignar = $request;
        DB::beginTransaction();
        try {
            AsignarGrado::where('pa_id', $datos_asignar['persona_id'])
                ->where('niv_id', $datos_asignar['nivel'])
                ->where('asig_is_deleted', '!=', 1)
                ->update(['asig_is_deleted' => 1]);
            foreach ($datos_asignar['grado'] as $value) {
                $array = explode(">", $value);

                $data = AsignarGrado::where('pa_id', $datos_asignar['persona_id'])
                    ->where('niv_id', $datos_asignar['nivel'])
                    ->where('gra_id', $array[0])
                    ->where('seccion', $array[1])
                    ->where('asig_is_deleted', '!=', 1)
                    ->get();

                if (count($data) == 0) {
                    //antes de asignar primero quiero que a todos los pongasn en  asig_is_deleted = 1

                    AsignarGrado::create([
                        'pa_id' => $datos_asignar['persona_id'],
                        'niv_id' => $datos_asignar['nivel'],
                        'gra_id' => $array[0],
                        'seccion' => $array[1]
                    ]);
                }
            }

            DB::commit();


            return response()->json([
                'status' => 200,
                'response' => 1
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return response()->json([
                'status' => 0
            ]);
        }
    }


    public function eliminacionMasiva(Request $request)
    {
        $datos_asignar = $request;
        $data = AsignarGrado::where('pa_id', $datos_asignar->persona_id)
        ->where('niv_id', $datos_asignar->nivel)
        ->update(['asig_is_deleted' => 1]);

        return response()->json([
            'status' => 200,
            'response' => 1
        ]);
    }

}
