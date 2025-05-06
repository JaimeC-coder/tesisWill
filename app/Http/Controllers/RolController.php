<?php

namespace App\Http\Controllers;

use App\Models\Rol;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
        $role = new Role();
        $permissions = Permission::all();
        $estado = $this->estado;

        return view('view.roles.create', compact('role', 'estado', 'permissions'));

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

            Role::create([
                'name' => $request->rol_descripcion,
                'guard_name' => 'web',
                'tipo' => 'usuario',
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
    public function show(Role $rol)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $estado = $this->estado;
        $permissions = Permission::all();

        return view('view.roles.edit', compact('role','permissions','estado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {



        DB::beginTransaction();
        try {

            $role->update(array_filter([
                'name' => $request->rol_descripcion,
                'rol_estado' => $request->rol_estado,
            ], fn($value) => !is_null($value) && $value !== ''));

            $role->syncPermissions($request->permissions);

            DB::commit();

            return redirect()->route('roles.inicio')->with('success', 'Año escolar eliminado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('roles.inicio')->with('error', 'Error al eliminar el año escolar');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        DB::beginTransaction();
        try {


            $role->permissions()->detach();
            $role->users()->detach();
            $role->delete();
            DB::commit();

            return redirect()->route('roles.inicio')->with('success', 'Año escolar eliminado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('roles.inicio')->with('error', 'Error al eliminar el año escolar');
        }
    }

    public function inicio()
    {
        $roles = Role::all();
        //return $roles;
        return view('view.roles.inicio', compact('roles'));
    }
}
