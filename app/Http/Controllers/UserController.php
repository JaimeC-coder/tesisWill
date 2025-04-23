<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Apoderado;
use App\Models\Departamento;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use function Illuminate\Log\log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public $sexo = ['M' => 'Masculino', 'F' => 'Femenino'];
    public $estadoCivil = ['S' => 'Soltero', 'C' => 'Casado', 'D' => 'Divorciado', 'V' => 'Viudo'];
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

        return view('view.usuario.create', compact('usuario', 'estados','sexo', 'estadoCivil', 'vive', 'parentesco', 'departamentos','roles'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $request;

        DB::beginTransaction();
        try {
            if(!$request->per_id){
                $Persona = Persona::create([
                    'per_dni' => $request->per_dni,
                    'per_nombres' => $request->nombres,
                    'per_apellidos' => $request->apellidos,//!falta
                    'per_email' => $request->emailhidden,
                    'per_sexo' => $request->per_sexo,
                    'per_fecha_nacimiento' => $request->per_fecha_nacimiento,
                    'per_estado_civil' => $request->per_estado_civil,
                    'per_pais' => $request->pais, //!falta
                    'per_departamento' => $request->per_departamento,
                    'per_provincia' => $request->per_provincia,
                    'per_distrito' => $request->per_distrito,
                    'per_direccion' => $request->per_direccion,
                ]);
                $request->per_id = $Persona->per_id;
            }

            User::create([
                'per_id' => $request->per_id,
                'name' => $request->nameUserhidden,
                'email' => $request->emailhidden,
                'password' => Hash::make($request->password),
                'rol_id' => 1,
                'estado' => $request->estado
            ]);

            DB::commit();

           return redirect()->route('usuario.inicio')->with('success', 'Usuario creado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->route('usuario.inicio')->with('error', 'Error al crear el usuario');
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

        return view('view.usuario.edit',compact('sexo','estados','estadoCivil','vive','parentesco','departamentos','usuario','roles'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        return $request;
        // $datos = $request['params']['data'];
        // $persona = Persona::find($datos['per_id']);
        // $persona->per_dni = $datos['dni'];
        // $persona->per_nombres = $datos['nombres'];
        // $persona->per_apellidos = $datos['apellidos'];
        // $persona->per_email = $datos['email'];
        // $persona->per_sexo = $datos['sexo'];
        // $persona->per_fecha_nacimiento = $datos['fecha_nacimiento'];
        // $persona->per_estado_civil = $datos['estado_civil'];
        // $persona->per_celular = $datos['celular'];
        // $persona->per_pais = $datos['pais'];
        // $persona->per_departamento = $datos['departamento'];
        // $persona->per_provincia = $datos['provincia'];
        // $persona->per_distrito = $datos['distrito'];
        // $persona->per_direccion = $datos['direccion'];
        // $persona->save();

        // $usuario = User::find($datos['id_user']);
        // $usuario->name = $datos['name'];
        // $usuario->email = $datos['email'];
        // $usuario->rol_id = $datos['id_rol'];
        // if(isset($datos['password']) == true){
        //     $usuario->password = Hash::make($datos['password']);
        // }
        // $usuario->estado = $datos['estado'];
        // $usuario->save();

        return redirect()->route('usuario.inicio')->with('success', 'Usuario actualizado correctamente');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $user->update([
                'is_deleted' => 1
            ]);

            DB::commit();

            return redirect()->route('usuario.inicio')->with('success', 'Año escolar eliminado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('usuario.inicio')->with('error', 'Error al eliminar el año escolar');
        }
    }

    public function inicio()
    {
        $usuarios = user::where('is_deleted', '!=', 1)->get();
       // return $alumnos;
        return view('view.usuario.inicio', compact('usuarios'));

    }
}
