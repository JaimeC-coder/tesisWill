<?php

namespace App\Http\Controllers;

use App\Models\Anio;
use App\Models\Curso;
use App\Models\Gsa;
use App\Models\Horario;
use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
            ->where('is_deleted', '!=', 1)
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
        $anios = Anio::where('is_deleted', '!=', 1)->get();
        return view('view.horario.inicio', compact('anios'));
    }

    public function search(Request $request)
    {


        $anio_id = $request->anio_id;
        $nivel_id = $request->nivel_id;
        $grado_id = $request->grado_id;
        $seccion_id = $request->seccion_id;
        $dias = $this->dias;
        $periodo = Periodo::where('anio_id', $anio_id)->first();
        $ags = Gsa::where('niv_id', $nivel_id)->where('gra_id', $grado_id)->where('sec_id', $seccion_id)->first();

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

        $cursos = Curso::where('cur_horas', '>', 0)
            ->where('niv_id', $nivel_id)
            ->where('gra_id', $grado_id)
            ->where('is_deleted', '!=', 1)
            ->select('cur_id', 'cur_nombre', 'cur_horas', 'cur_abreviatura')
            ->get();

        return response()->json([
            'status' => 200,
            'horarios' => $horarios,
            'cursos' => $cursos,
            'dias' => $dias
        ]);

    }
}
