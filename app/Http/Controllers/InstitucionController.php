<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Institucion;
use App\Models\Provincia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstitucionController extends Controller
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
    public function show(Institucion $institucion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Institucion $institucion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Institucion $institucion)
    {

        DB::beginTransaction();
        try {
                $institucion->update([
                    'ie_codigo_modular' =>$request->ie_codigo_modular,
                    'ie_anexo' =>$request->ie_anexo,
                    'ie_nivel' =>$request->ie_nivel,
                    'ie_nombre' =>$request->ie_nombre,
                    'ie_gestion' =>$request->ie_gestion,
                    'ie_departamento' =>$request->ie_departamento,
                    'ie_provincia' =>$request->ie_provincia,
                    'ie_distrito' =>$request->ie_distrito,
                    'ie_direccion' =>$request->ie_direccion,
                    'ie_dre' =>$request->ie_dre,
                    'ie_ugel' =>$request->ie_ugel,
                    'ie_genero' =>$request->ie_genero,
                    'ie_turno' =>$request->ie_turno,
                    'ie_dias_laborales' =>$request->ie_dias_laborales,
                    'ie_director' =>$request->ie_director,
                    'ie_telefono' =>$request->ie_telefono,
                    'ie_email' =>$request->ie_email,
                    'ie_web' =>$request->ie_web,
                    'is_deleted'
                ]);


            DB::commit();

            return redirect()->route('institucion.inicio')->with('success', 'Año escolar creado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('institucion.edit')->with('error', 'Error al crear el aula');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Institucion $institucion)
    {
        //
    }

    public function inicio()
    {
        $institucion = Institucion::first();
        $departamentos = Departamento::all();
        $provincias = Provincia::all();
        $distritos = Distrito::all();
        return view('view.institucionEducativa.inicio', compact('institucion', 'departamentos', 'provincias', 'distritos'));


    }

}
