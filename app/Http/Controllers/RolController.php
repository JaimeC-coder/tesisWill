<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolController extends Controller
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
    public function show(Rol $rol)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rol $rol)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rol $rol)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rol $rol)
    {
        DB::beginTransaction();
        try {
            $rol->update([
                'is_deleted' => 1
            ]);

            DB::commit();

            return redirect()->route('rol.inicio')->with('success', 'Año escolar eliminado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('rol.inicio')->with('error', 'Error al eliminar el año escolar');
        }
    }

    public function inicio()
    { $roles = Rol::where('rol_estado', 1)->where('is_deleted','!=',1)->get();
        //return $roles;
        return view('view.roles.inicio', compact('roles'));

    }
}

