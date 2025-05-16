<?php

namespace App\Http\Controllers;

use App\Models\Anio;
use App\Models\Periodo;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodoController extends Controller
{
    public $estado = [
        '2' => 'Finalizado',
        '1' => 'Aperturado'
    ];
    public $anio;
    public $tipo;

    public function __construct()
    {
        $this->anio = Anio::where('anio_estado', '!=', 0)->where('is_deleted',0)->get();
        $this->tipo = Tipo::where('is_deleted', '!=', 1)->get();
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
        $periodo = new Periodo();
        $anio = $this->anio;
        $tipos = $this->tipo;
        $estado = $this->estado;
        return view('view.periodoAcademico.create', compact('periodo', 'anio', 'tipos', 'estado'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            Periodo::create([
                'anio_id' => $request->anio_id,
                'per_inicio_matriculas' => $request->per_inicio_matriculas,
                'per_final_matricula' => $request->per_final_matricula,
                'per_limite_cierre' => $request->per_limite_cierre,
                 'per_tp_notas' => 4,
                'per_estado' => $request->per_estado == 1 ? 1 : 0,
            ]);

            DB::commit();

            return redirect()->route('periodo.inicio')->with('success', 'Año escolar creado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('periodo.create')->with('error', 'Error al crear el aula');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Periodo $periodo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Periodo $periodo)
    {

        $anio = $this->anio;
        $tipos = $this->tipo;
        $estado = $this->estado;
        return view('view.periodoAcademico.edit', compact('periodo', 'anio', 'tipos', 'estado'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Periodo $periodo)
    {


        DB::beginTransaction();
        try {
            $periodo->update([
                'anio_id' => $request->anio_id,
                'per_inicio_matriculas' => $request->per_inicio_matriculas,
                'per_final_matricula' => $request->per_final_matricula,
                'per_limite_cierre' => $request->per_limite_cierre,
                'per_tp_notas' => 4,
                'per_estado' => $request->per_estado == 1 ? 1 : 0,
            ]);

            DB::commit();

            return redirect()->route('periodo.inicio')->with('success', 'Año escolar creado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('periodo.create')->with('error', 'Error al crear el aula');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Periodo $periodo)
    {
        //
        DB::beginTransaction();
        try {
            $periodo->update([
                'is_deleted' => 1,
            ]);

            DB::commit();

            return redirect()->route('periodo.inicio')->with('success', 'Año escolar eliminado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('periodo.inicio')->with('error', 'Error al eliminar');
        }
    }

    public function inicio()
    {
        $periodos = Periodo::where('is_deleted','!=',1)->get();
        return view('view.periodoAcademico.inicio', compact('periodos'));
    }
}
