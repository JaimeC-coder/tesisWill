<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public $sexo = ['Masculino' => 'Masculino', 'Femenino' => 'Femenino'];
    public $estadoCivil = ['Soltero' => 'Soltero', 'Casado' => 'Casado', 'Divorciado' => 'Divorciado', 'Viudo' => 'Viudo'];
    public $vive = [1 => 'Si', 2 => 'No'];
    public $estados = ['0' => 'Activo', '1' => 'Inactivo'];
    public $parentesco = ['Madre' => 'Madre', 'Padre' => 'Padre', 'TUTOR' => 'Tutor'];

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuario = new User();
        $sexo = $this->sexo;
        $estados = $this->estados;
        $estadoCivil = $this->estadoCivil;
        $vive = $this->vive;
        $parentesco = $this->parentesco;
        $roles = Role::all();
        $departamentos = Departamento::all();

        return view('view.usuario.create', compact('usuario', 'estados', 'sexo', 'estadoCivil', 'vive', 'parentesco', 'departamentos', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        DB::beginTransaction();
        try {
            if (!$request->per_id || $request->per_id == null) {
                $Persona = Persona::create([
                    'per_dni' => $request->per_dni,
                    'per_nombres' => Str::ucfirst($request->nombreshidden),
                    'per_apellidos' => Str::ucfirst($request->apellidoshidden),
                    'per_nombre_completo' => Str::ucfirst($request->nombreshidden) . ' ' . Str::ucfirst($request->apellidoshidden),
                    'per_email' => $request->emailhidden,
                    'per_sexo' => $request->per_sexo,
                    'per_fecha_nacimiento' => $request->per_fecha_nacimiento,
                    'per_estado_civil' => $request->per_estado_civil,
                    'per_pais' => $request->paishidden ?? 'PERU',
                    'per_celular' => $request->per_celular,
                    'per_departamento' => $request->per_departamento,
                    'per_provincia' => $request->per_provincia,
                    'per_distrito' => $request->per_distrito,
                    'per_direccion' => $request->per_direccion,
                ]);
                $request['per_id'] = $Persona->per_id;
            }

            $user = User::create([
                'per_id' => $request->per_id,
                'name' => Str::ucfirst($request->nameUserhidden) ,
                'email' => $request->emailhidden,
                'password' => Hash::make($request->password),
                'rol_id' => 1,
                'estado' => $request->estado
            ]);
            $user->assignRole($request->rolName);


            DB::commit();

            return redirect()->route('usuarios.inicio')->with('success', 'Usuario creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('usuarios.inicio')->with('error', 'Error al crear el usuario');
        }
    }



    public function edit(User $usuario)
    {
        $roles = Role::all();
        $estados = $this->estados;
        $sexo = $this->sexo;
        $estadoCivil = $this->estadoCivil;
        $vive = $this->vive;
        $parentesco = $this->parentesco;
        $departamentos = Departamento::all();

        return view('view.usuario.edit', compact('sexo', 'estados', 'estadoCivil', 'vive', 'parentesco', 'departamentos', 'usuario', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
       // return $request->all();
        $persona = Persona::find($request->per_id);
        //$persona->per_dni = $request->dni;
        $persona->per_nombres = $request->nombreshidden;
        $persona->per_apellidos = $request->apellidoshidden;
        $persona->per_email = $request->emailhidden;
        $persona->per_sexo = $request->per_sexo;
        $persona->per_fecha_nacimiento = $request->per_fecha_nacimiento;
        $persona->per_estado_civil = $request->per_estado_civil;
        $persona->per_celular = $request->per_celular;
        $persona->per_pais = $request->paishidden ?? 'PERU';

        $persona->per_direccion = $request->per_direccion;
        $persona->save();

        $usuario = $usuario = User::where('per_id', $request->per_id)->first();

        $usuario->name = $request->nameUserhidden;
        $usuario->email = $request->emailhidden;
        $usuario->rol_id = 1;
        if (isset($request->password) == true) {
            $usuario->password = Hash::make($request->password);
        }
        $usuario->estado = $request->estado;

        $usuario->syncRoles($request->rolName);

        $usuario->save();

        return redirect()->route('usuarios.inicio')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {

        DB::beginTransaction();
        try {
            $usuario->update([
                'is_deleted' => 1
            ]);

            DB::commit();

            return redirect()->route('usuarios.inicio')->with('success', 'Año escolar eliminado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar el año escolar: ' . $e->getMessage());
            DB::rollBack();
            return redirect()->route('usuarios.inicio')->with('error', 'Error al eliminar el año escolar');
        }
    }

    public function inicio()
    {
        $usuarios = user::where('is_deleted', '!=',1 )->orderBy('created_at', 'desc')->get();
        // return $alumnos;
        return view('view.usuario.inicio', compact('usuarios'));
    }
}
