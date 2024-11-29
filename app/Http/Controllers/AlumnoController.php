<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumnoController extends Controller
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
    public function show(Alumno $alumno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alumno $alumno)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alumno $alumno)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumno $alumno)
    {
        DB::beginTransaction();
        try {
            $alumno->update([
                'is_deleted' => 1
            ]);

            DB::commit();

            return redirect()->route('alumno.inicio')->with('success', 'Año escolar eliminado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('alumno.inicio')->with('error', 'Error al eliminar el año escolar');
        }
    }

    public function inicio()
    {
        $alumnos = Alumno::where('is_deleted', '!=', 1)->get();
       // return $alumnos;
        return view('view.alumno.inicio', compact('alumnos'));

    }
}
