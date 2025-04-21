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
        $matricula = New Matricula();

        return view('view.matricula.create', compact('matricula'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $request;
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
 

    public function showNiveles(Request $request)
    {
        $niveles = Nivel::get();
        return response()->json($niveles);
       /*  if ($request->ajax()) {
            return response()->json($niveles);
        } */
        return view('Error404');
    }

    public function showGrados(Request $request)
    {
        $nivel = $request['params']['id'];
        $grados = Grado::where('niv_id',$nivel)->get();
        return response()->json($grados);
       /*  if ($request->ajax()) {
            return response()->json($grados);
        }
        return view('Error404'); */
    }

    public function showSecciones(Request $request)
    {
        $grado = $request['params']['id'];
        $secciones = Seccion::where('gra_id',$grado)->get();
        return response()->json($secciones);
        /* if ($request->ajax()) {
            return response()->json($secciones);
        }
        return view('Error404'); */
    }

    public function infoSecciones(Request $request)
    {
        $data = $request['params']['data'];
        $seccion = Seccion::where('sec_id',$data)->first();
        $aula = Aula::where('ala_id',$seccion->sec_aula)->first();
        $seccion->aula = $aula->ala_descripcion;
        $seccion->ala_id = $aula->ala_id;
        return response()->json($seccion);
        /* if ($request->ajax()) {
            return response()->json($seccion);
        }
        return view('Error404'); */
    }
}
