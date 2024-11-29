<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CursoController extends Controller
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
    public function show(Curso $curso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curso $curso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Curso $curso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curso $curso)
    {
        DB::beginTransaction();
        try {
            $curso->update([
                'is_deleted' => 1
            ]);

            DB::commit();

            return redirect()->route('curso.inicio')->with('success', 'Año escolar eliminado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('curso.inicio')->with('error', 'Error al eliminar el año escolar');
        }
    }

    public function inicio()
    {
        $cursos = Curso::where('is_deleted','!=',1)->orderBy('cur_id', 'desc')->get();
        //return $cursos[0]->capacidad;

        return view('view.curso.inicio', compact('cursos'));
    }
}
