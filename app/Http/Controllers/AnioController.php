<?php

namespace App\Http\Controllers;

use App\Models\Anio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $estado = [1 => 'Activo', 2 => 'Inactivo'];
    public $taller = [1 => 'Si', 2 => 'No'];
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $anio = new Anio();
        $estado = $this->estado;
        $taller = $this->taller;

        return view('view.anioEscolar.create',compact('anio','estado','taller'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        DB::beginTransaction();
        try {
            Anio::create([
                'anio_descripcion' => $request->anio_descripcion,
                'anio_fechaInicio' => $request->anio_fechaInicio,
                'anio_fechaFin' => $request->anio_fechaFin,
                'anio_duracionHoraAcademica' => $request->anio_duracionHoraAcademica,
                'anio_duracionHoraLibre' => $request->anio_duracionHoraLibre,
                'anio_cantidadPersonal' => $request->anio_cantidadPersonal,
                'anio_tallerSeleccionable' => $request->anio_tallerSeleccionable,
                'anio_estado' => $request->anio_estado
            ]);

            DB::commit();

           return redirect()->route('anio.inicio')->with('success', 'Año escolar creado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('anio.create')->with('error', 'Error al crear el aula');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Anio $anio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anio $anio)
    {
        //
        $estado = $this->estado;
        $taller = $this->taller;
        return view('view.anioEscolar.edit', compact('anio','estado','taller'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Anio $anio)
    {
        //
        DB::beginTransaction();
        try {
            $anio->update([
                'anio_descripcion' => $request->anio_descripcion,
                'anio_fechaInicio' => $request->anio_fechaInicio,
                'anio_fechaFin' => $request->anio_fechaFin,
                'anio_duracionHoraAcademica' => $request->anio_duracionHoraAcademica,
                'anio_duracionHoraLibre' => $request->anio_duracionHoraLibre,
                'anio_cantidadPersonal' => $request->anio_cantidadPersonal,
                'anio_tallerSeleccionable' => $request->anio_tallerSeleccionable,
                'anio_estado' => $request->anio_estado
            ]);

            DB::commit();

            return redirect()->route('anio.inicio')->with('success', 'Año escolar actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('anio.edit')->with('error', 'Error al actualizar el año escolar');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anio $anio)
    {
        //
    }

    public function inicio()
    {
        $anio = Anio::where('is_deleted','!=',1)->orderBy('anio_id', 'desc')->get();
        return view('view.anioEscolar.inicio', compact('anio'));
    }
}
