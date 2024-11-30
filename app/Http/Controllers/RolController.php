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
    public $estado = [
        1 => 'Habilitado',
        0 => 'Deshabilitado'
    ];
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rol = new Rol();
        $estado = $this->estado;

        return view('view.roles.create', compact('rol', 'estado'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        DB::beginTransaction();
        try {
            Rol::create([
                'rol_descripcion' => $request->rol_descripcion,
                'rol_estado' => $request->rol_estado,
            ]);

            DB::commit();

            return redirect()->route('roles.inicio')->with('success', 'Año escolar eliminado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('roles.inicio')->with('error', 'Error al eliminar el año escolar');
        }
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
    public function destroy(Rol $role)
    {
        DB::beginTransaction();
        try {
            $role->update([
                'is_deleted' => 1
            ]);

            DB::commit();

            return redirect()->route('roles.inicio')->with('success', 'Año escolar eliminado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('roles.inicio')->with('error', 'Error al eliminar el año escolar');
        }
    }

    public function inicio()
    {
        $roles = Rol::where('rol_estado', 1)->where('is_deleted', '!=', 1)->get();
        //return $roles;
        return view('view.roles.inicio', compact('roles'));
    }
}
