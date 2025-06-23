<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Anio;
use App\Models\AsignarCurso;
use App\Models\Curso;
use App\Models\Gsa;
use App\Models\Horario;
use App\Models\Matricula;
use App\Models\Periodo;
use App\Models\PersonalAcademico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $dias = [
        [
            'id' => 'monday',
            'name' => 'Lunes'
        ],
        [
            'id' => 'tuesday',
            'name' => 'Martes'
        ],
        [
            'id' => 'wednesday',
            'name' => 'Miercoles'
        ],
        [
            'id' => 'thursday',
            'name' => 'Jueves'
        ],
        [
            'id' => 'friday',
            'name' => 'Viernes'
        ],
        [
            'id' => 'saturday',
            'name' => 'Sabado'
        ],
        [
            'id' => 'sunday',
            'name' => 'Domingo'
        ],
    ];
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        log($request);


        $niv_id = $request->nivel_id;
        $gra_id = $request->grado_id;
        $cur_id = $request->cur_id;
        $anio_id = $request->anio_id;
        $dia_id = $request->dia_id;
        $color = $request->color;
        $hora_inicio = $request->hora_inicio;
        $hora_fin = $request->hora_fin;
        $seccion_id = $request->seccion_id;

        $anio = Anio::where('anio_id', $anio_id)
            ->where('anio_estado', '!=', 0)
            ->first();

        $fecha_inicio = Carbon::parse($anio->anio_fechaInicio);
        $fecha_final = Carbon::parse($anio->anio_fechaFin);

        $periodo = Periodo::where('anio_id', $anio_id)->first();
        $ags = gsa::where('niv_id', $niv_id)->where('gra_id', $gra_id)->where('sec_id', $seccion_id)->first();
        // Mapa para relacionar los días con los métodos de Carbon
        $dayMethods = [
            'monday' => 'isMonday',
            'tuesday' => 'isTuesday',
            'wednesday' => 'isWednesday',
            'thursday' => 'isThursday',
            'friday' => 'isFriday',
            'saturday' => 'isSaturday',
            'sunday' => 'isSunday',
        ];

        if (!array_key_exists($dia_id, $dayMethods)) {
            return response()->json(['status' => 0, 'error' => 'Día inválido.'], 400);
        }

        $dayMethod = $dayMethods[$dia_id];

        DB::beginTransaction();
        try {
            while ($fecha_inicio->lte($fecha_final)) {
                if ($fecha_inicio->$dayMethod()) {
                    Horario::create([
                        'per_id' => $periodo->per_id,
                        'ags_id' => $ags->ags_id,
                        'cur_id' => $cur_id,
                        'fecha' => $fecha_inicio->toDateString(),
                        'hora_inicio' => $hora_inicio,
                        'hora_fin' => $hora_fin,
                        'color' => $color,
                        'editable' => false,
                    ]);
                }

                $fecha_inicio->addDay();
            }

            DB::commit();

            return response()->json(['status' => 1]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 0, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Horario $horario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horario $horario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horario $horario)
    {
        //
    }

    public function inicio()
    {
        $user = Auth::user();
        $user = $user->per_id;

        $anios =  Periodo::where('per_estado', '!=', 0)
            ->join('anios', 'periodos.anio_id', '=', 'anios.anio_id')
            ->select('anios.anio_id', 'anios.anio_descripcion')
            ->where('periodos.is_deleted', 0)
            ->where('anios.is_deleted', 0)
            ->get();
        Log::info($anios);

        return view('view.horario.inicio', compact('anios', 'user'));
    }

    public function search(Request $request)
    {
        Log::info('request');
        Log::info($request->all());


        $anio_id = $request->anio_id;
        $nivel_id = $request->nivel_id;
        $grado_id = $request->grado_id;
        $seccion_id = $request->seccion_id;
        $per_id = $request->user_id;

        $anioselect = Anio::where('anio_id', $anio_id)
            ->first();

        $personal = PersonalAcademico::where('per_id', $per_id)->where('is_deleted', '!=', 1)->first();
        Log::info('---Personal---');
        Log::info($personal);
        Log::info('-------------');
        $usuario = User::Where('per_id', $per_id)->where('is_deleted', '!=', 1)->first();
        Log::info('---Usuario---');
        Log::info($usuario);
        Log::info('-------------');


        $dias = $this->dias;
        Log::info($dias);
        $periodo = Periodo::where('anio_id', $anio_id)->where('is_deleted', '!=', 1)->first();
        Log::info('---Periodo---');
        Log::info($periodo);
        Log::info('-------------');

        $ags = Gsa::where('niv_id', $nivel_id)->where('gra_id', $grado_id)->where('sec_id', $seccion_id)->first();
        Log::info('---GSA---');
        Log::info($ags);
        Log::info('-------------');

        if (!$periodo) {
            Log::info('No se encontro curso para el año seleccionado PERIODO');
            return response()->json([
                'status' => 400,
                'mensaje' => 'No se encontro curso para el año seleccionado',
                'horarios' => [],
                'cursos' => [],
                'dias' => $dias
            ]);
        }
        if (!$ags) {
            Log::info('No se encontro curso para el nivel y grado seleccionado GSA');
            return response()->json([
                'status' => 400,
                'mensaje' => 'No se encontro alumnos registrados para el nivel y grado seleccionado',
                'cursos' => [],
                'dias' => $dias
            ]);
        }

        $horarios = Horario::where('per_id', $periodo->per_id)->where('ags_id', $ags->ags_id)->where('is_deleted', '!=', 1)->get();
        foreach ($horarios as $value) {
            $curso = Curso::where('cur_id', $value->cur_id)->where('is_deleted', '!=', 1)->first();
            if (!$curso) {
                $value->title = 'Recreo';
                $value->start = $value->fecha . ' ' . substr($value->hora_inicio, 0, 5);
                $value->end = date("Y-m-d", strtotime($value->fecha . "+ 4 day")) . ' ' . substr($value->hora_fin, 0, 5);
            } else {
                $value->title = $curso->cur_abreviatura;
                $value->start = $value->fecha . ' ' . substr($value->hora_inicio, 0, 5);
                $value->end = $value->fecha . ' ' . substr($value->hora_fin, 0, 5);
            }
        }
        Log::info('---Horarios Modificados---');
        Log::info($horarios);
        Log::info('-------------');

        if ($usuario->roles[0]->name == "Docente") {
            Log::info('---Docente---');
            Log::info($usuario->roles[0]->name);
            Log::info('-------------');

            $asignarCursos = AsignarCurso::where('pa_id', $personal->pa_id)
                ->where('asignar_cursos.niv_id', $nivel_id)
                ->where('cursos.gra_id', $grado_id)
                ->where('cursos.niv_id', $nivel_id)
                ->where('cursos.is_deleted', '!=', 1)
                ->where('cursos.cur_horas', '>', 0)
                ->where('asig_is_deleted', '!=', 1)
                ->join('cursos', 'asignar_cursos.curso', '=', 'cursos.cur_nombre')
                ->select('cursos.cur_id', 'cursos.cur_nombre', 'cursos.cur_abreviatura', 'cursos.cur_horas')
                ->get();
        } else {
            $asignarCursos = Curso::where('cur_horas', '>', 0)
                ->where('niv_id', $nivel_id)
                ->where('gra_id', $grado_id)
                ->where('is_deleted', '!=', 1)
                ->select('cur_id', 'cur_nombre', 'cur_horas', 'cur_abreviatura')
                ->get();
            Log::info('---Asignar Cursos---');
            Log::info($asignarCursos);
            Log::info('-------------');
        }


        // if ($usuario->roles[0]->name == "Alumno" or $usuario->roles[0]->name == "Administrador" or $usuario->roles[0]->name == "Secretaria" or $usuario->roles[0]->name == "Director") {

        // } elseif ($usuario->roles[0]->name == "Docente" ) {

        // }


        return response()->json([
            'status' => 200,
            'horarios' => $horarios,
            'cursos' => $asignarCursos,
            'dias' => $dias,
            'anio' => $anioselect->anio_fechaInicio,
        ]);
    }

    public function verifyAlumno(Request $request)
    {


        $user = User::Where('per_id', $request->user)
            ->where('is_deleted', '!=', 1)
            ->first();



        if ($user->roles[0]->name == "Alumno") {
            $matricula  = Matricula::where('alu_id', $user->persona->alumno->alu_id)
                ->where('is_deleted', '!=', 1)
                ->first();
            if (!$matricula) {
                return response()->json([
                    'status' => 200,
                    'alumno' => 2,
                    'data' => "El alumno no se encuentra matriculado",
                ]);
            }

            Log::info($matricula);
            $anio = $matricula->periodo->anio->anio_id;
            $nivel = $matricula->gsa->nivel->niv_id;
            $grado = $matricula->gsa->grado->gra_id;
            $seccion = $matricula->gsa->seccion->sec_id;

            return  response()->json([
                'status' => 200,
                'alumno' => 1,
                'data' => [
                    'anio' => $anio,
                    'nivel' => $nivel,
                    'grado' => $grado,
                    'seccion' => $seccion
                ]
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'alumno' => 0,
                'data' => null,
            ]);
        }
    }
}
