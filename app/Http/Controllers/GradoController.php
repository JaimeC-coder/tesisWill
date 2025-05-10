<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\Periodo;
use App\Models\PersonalAcademico;
use App\Models\Seccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GradoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $nivel;
    public function __construct()
    {
        $this->nivel = Nivel::all();
    }
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
        $grado = new Grado();
        $niveles = $this->nivel;
        return view('view.gradoSeccion.create', compact('grado', 'niveles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $newRequest = $request->all();

        DB::transaction(function () use ($newRequest) {
            $datos = explode(',', $newRequest['gra_descripcion']);
            foreach ($datos as $dato) {
                Grado::create([
                    'gra_descripcion' => $dato,
                    'niv_id' => $newRequest['niv_id'],
                ]);
            }
        });

        return redirect()->route('gradoSeccion.inicio');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grado $grado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grado $gradoSeccion)
    {
        //
        $niveles = $this->nivel;
        $grado = $gradoSeccion;
        return view('view.gradoSeccion.edit', compact('grado', 'niveles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grado $gradoSeccion)
    {
        //
        $gradoSeccion->update($request->all());

        return redirect()->route('gradoSeccion.inicio');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grado $gradoSeccion)
    {
        //
        Log::info($gradoSeccion);
        $gradoSeccion->update([
            'gra_is_delete' => 1
        ]);
        $secciones = Seccion::where('gra_id', $gradoSeccion->gra_id)->get();
        foreach ($secciones as $seccion) {
            $seccion->update([
                'sec_is_delete' => 1
            ]);
        }
        return redirect()->route('gradoSeccion.inicio');
    }

    public function inicio()
    {
        $grados = Grado::where('gra_is_delete', '!=', 1)->orderBy('niv_id', 'desc')->get();

        return view('view.gradoSeccion.inicio', compact('grados'));
    }

    public function secciongrado(Request $request, Grado $seccionAdd2)
    {

        $periodos = Periodo::where('is_deleted', 0)->where('per_estado', 1)->get();
        //$aulas = Aula::orderBy('ala_id', 'asc')->where('ala_en_uso', '!=', 1)->orwhere('is_multiuse',1)->where('ala_tipo', '!=', 'Oficina')->where('ala_tipo', '!=', 'Extra')->where('ala_is_delete', '!=', 1)->get();

        $aulas = Aula::where(function ($query) {
            $query->where('ala_en_uso', '!=', 1)
                ->orWhere('is_multiuse', 1);
        })
            ->where('ala_tipo', '!=', 'OFICINA')
            ->where('ala_tipo', '!=', 'EXTRA')
            ->where('ala_is_delete', '!=', 1)
            ->orderBy('ala_id', 'asc')
            ->get();

        $tutores = PersonalAcademico::where('pa_is_tutor', 1)->where('is_deleted', '!=', 1)->get();
        return view('view.gradoSeccion.addIfoGrado', compact('seccionAdd2', 'periodos', 'aulas', 'tutores'));
    }
    public function secciongradoRegister(Request $request, $seccionAdd2)
    {

        $newRequest = $request->all();
        $newRequest['seccion_id'] = $seccionAdd2;

        DB::transaction(function () use ($newRequest) {
            $datos = explode(',', $newRequest['descripcion']);
            foreach ($datos as $value) {
                Seccion::create([
                    'sec_descripcion' => $value,
                    'sec_tutor' => $newRequest['tutor'],
                    'sec_aula' => $newRequest['aula'],
                    'gra_id' => $newRequest['seccion_id'],
                    'sec_periodo' => $newRequest['periodo'],
                    'sec_vacantes' => $newRequest['vacantes']
                ]);

                $aula = Aula::find($newRequest['aula']);
                $aula->ala_en_uso = 1;
                $aula->save();
            }
        });

        return redirect()->route('gradoSeccion.inicio');
    }
}
