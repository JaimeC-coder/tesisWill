<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Provincia;
use Illuminate\Http\Request;

use function Illuminate\Log\log;

class DepartamentoController extends Controller
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
    public function show(Departamento $departamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departamento $departamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departamento $departamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departamento $departamento)
    {
        //
    }

    public function searchProvincia(Request $request)
    {

        try {

        $provincias = Departamento::find($request->dep_id)->provincia;

        return response()->json($provincias);

        } catch (\Throwable $th) {
            return response()->json($th);
        }

    }

    public function searchDistrito(Request $request)
    {
       try {
        $distritos = Provincia::find($request->prov_id)->distrito;
        return response()->json($distritos);
       } catch (\Throwable $th) {
              return response()->json($th);
       }
    }
}
