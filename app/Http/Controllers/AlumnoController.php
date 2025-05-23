<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Apoderado;
use App\Models\Departamento;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public $sexo = ['Masculino' => 'Masculino', 'Femenino' => 'Femenino'];
    public $estadoCivil = ['Soltero' => 'Soltero', 'Casado' => 'Casado', 'Divorciado' => 'Divorciado', 'Viudo' => 'Viudo'];
    public $vive = [1 => 'Si', 2 => 'No'];
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
        $alumno = new Alumno();
        $sexo = $this->sexo;
        $estadoCivil = $this->estadoCivil;
        $vive = $this->vive;
        $parentesco = $this->parentesco;
        $departamentos = Departamento::all();

        return view('view.alumno.create', compact('alumno', 'sexo', 'estadoCivil', 'vive', 'parentesco', 'departamentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $newRequest = $request->all();
        Log::info($newRequest);
        DB::transaction(function () use ($newRequest) {
            if (isset($newRequest['per_id_Alumno']) && $newRequest['per_id_Alumno'] != null && isset($newRequest['per_id_Apoderado']) && $newRequest['per_id_Apoderado'] != null) {

                $Persona = Persona::find($newRequest['per_id_Alumno']);
                $Persona->update(array_filter([
                    'per_dni' => $newRequest['per_dni_Alumno'] ?? null,
                    'per_nombres' => $newRequest['per_nombres_Alumno'] ?? null,
                    'per_apellidos' => $newRequest['per_apellidos_Alumno'] ?? null,
                    'per_nombre_completo' => isset($newRequest['per_nombres_Alumno'], $newRequest['per_apellidos_Alumno']) ? $newRequest['per_nombres_Alumno'] . ' ' . $newRequest['per_apellidos_Alumno'] : null,
                    'per_email' => $newRequest['per_email_Alumno'] ?? null,
                    'per_sexo' => $newRequest['per_sexo_Alumno'] ?? null,
                    'per_fecha_nacimiento' => $newRequest['per_fecha_nacimiento_Alumno'] ?? null,
                    'per_estado_civil' => $newRequest['per_estado_civil_Alumno'] ?? null,
                    'per_celular' => $newRequest['per_celular_Alumno'] ?? null,
                    'per_pais' => $newRequest['per_pais_Alumno'] ?? null,
                    'per_departamento' => $newRequest['per_departamento_Alumno'] ?? null,
                    'per_provincia' => $newRequest['per_provincia_Alumno'] ?? null,
                    'per_distrito' => $newRequest['per_distrito_Alumno'] ?? null,
                    'per_direccion' => $newRequest['per_direccion_Alumno'] ?? null,
                ], fn($value) => !is_null($value) && $value !== ''));

                $persona2 = Persona::find($newRequest['per_id_Apoderado']);
                $persona2->update(array_filter([
                    'per_dni' => $newRequest['per_dni_Alumno'] ?? null,
                    'per_nombres' => $newRequest['per_nombres_Alumno'] ?? null,
                    'per_apellidos' => $newRequest['per_apellidos_Alumno'] ?? null,
                    'per_nombre_completo' => isset($newRequest['per_nombres_Alumno'], $newRequest['per_apellidos_Alumno']) ? $newRequest['per_nombres_Alumno'] . ' ' . $newRequest['per_apellidos_Alumno'] : null,
                    'per_email' => $newRequest['per_email_Alumno'] ?? null,
                    'per_sexo' => $newRequest['per_sexo_Alumno'] ?? null,
                    'per_fecha_nacimiento' => $newRequest['per_fecha_nacimiento_Alumno'] ?? null,
                    'per_estado_civil' => $newRequest['per_estado_civil_Alumno'] ?? null,
                    'per_celular' => $newRequest['per_celular_Alumno'] ?? null,
                    'per_pais' => $newRequest['per_pais_Alumno'] ?? null,
                    'per_departamento' => $newRequest['per_departamento_Alumno'] ?? null,
                    'per_provincia' => $newRequest['per_provincia_Alumno'] ?? null,
                    'per_distrito' => $newRequest['per_distrito_Alumno'] ?? null,
                    'per_direccion' => $newRequest['per_direccion_Alumno'] ?? null,
                ], fn($value) => !is_null($value) && $value !== ''));

                $Apoderado = Apoderado::create([
                    'per_id' => $newRequest['per_id_Apoderado'],
                    'apo_parentesco' => $newRequest['per_parentesco_Apoderado'],
                    'apo_vive_con_estudiante' => $newRequest['per_vive_con_estudiante_Apoderado'],
                ]);
            } else if ($newRequest['per_id_Alumno'] && $newRequest['per_id_Alumno'] != null && !$newRequest['per_id_Apoderado'] && $newRequest['per_id_Apoderado'] == null) { //apoderado
                log('entro else if 2');
                $per_id_Apoderado = Persona::create([
                    'per_dni' => $newRequest['per_dni_Apoderado'],
                    'per_nombres' => $newRequest['per_nombres_Apoderado'],
                    'per_apellidos' => $newRequest['per_apellidos_Apoderado'],
                    'per_nombre_completo' => $newRequest['per_nombres_Apoderado'] . ' ' . $newRequest['per_apellidos_Apoderado'],
                    'per_email' => $newRequest['per_email_Apoderado'],
                    'per_sexo' => $newRequest['per_sexo_Apoderado'],
                    'per_fecha_nacimiento' => $newRequest['per_fecha_nacimiento_Apoderado'],
                    'per_estado_civil' => $newRequest['per_estado_civil_Apoderado'],
                    'per_celular' => $newRequest['per_celular_Apoderado'],
                    'per_pais' => $newRequest['per_pais_Apoderado'],
                    'per_departamento' => $newRequest['per_departamento_Apoderado'],
                    'per_provincia' => $newRequest['per_provincia_Apoderado'],
                    'per_distrito' => $newRequest['per_distrito_Apoderado'],
                    'per_direccion' => $newRequest['per_direccion_Apoderado']
                ]);

                $Persona = Persona::find($newRequest['per_id_Alumno']);
                $Persona->update(array_filter([
                    'per_dni' => $newRequest['per_dni_Alumno'] ?? null,
                    'per_nombres' => $newRequest['per_nombres_Alumno'] ?? null,
                    'per_apellidos' => $newRequest['per_apellidos_Alumno'] ?? null,
                    'per_nombre_completo' => isset($newRequest['per_nombres_Alumno'], $newRequest['per_apellidos_Alumno']) ? $newRequest['per_nombres_Alumno'] . ' ' . $newRequest['per_apellidos_Alumno'] : null,
                    'per_email' => $newRequest['per_email_Alumno'] ?? null,
                    'per_sexo' => $newRequest['per_sexo_Alumno'] ?? null,
                    'per_fecha_nacimiento' => $newRequest['per_fecha_nacimiento_Alumno'] ?? null,
                    'per_estado_civil' => $newRequest['per_estado_civil_Alumno'] ?? null,
                    'per_celular' => $newRequest['per_celular_Alumno'] ?? null,
                    'per_pais' => $newRequest['per_pais_Alumno'] ?? null,
                    'per_departamento' => $newRequest['per_departamento_Alumno'] ?? null,
                    'per_provincia' => $newRequest['per_provincia_Alumno'] ?? null,
                    'per_distrito' => $newRequest['per_distrito_Alumno'] ?? null,
                    'per_direccion' => $newRequest['per_direccion_Alumno'] ?? null,
                ], fn($value) => !is_null($value) && $value !== ''));



                $Apoderado = Apoderado::create([
                    'per_id' => $per_id_Apoderado->per_id,
                    'apo_parentesco' => $newRequest['per_parentesco_Apoderado'],
                    'apo_vive_con_estudiante' => $newRequest['per_vive_con_estudiante_Apoderado'],
                ]);
                Log::info($per_id_Apoderado);
                $perUser = User::create([
                    'per_id' => $per_id_Apoderado->per_id,
                    'email' => $newRequest['per_email_Apoderado'],
                    'password' => bcrypt($newRequest['per_dni_Apoderado']),
                    'name' => $per_id_Apoderado->per_nombre_completo,
                    'rol_id' => 1
                ]);
                $perUser->assignRole('Apoderado');
            } else if (!isset($newRequest['per_id_Alumno']) && $newRequest['per_id_Alumno'] == null && isset($newRequest['per_id_Apoderado']) && $newRequest['per_id_Apoderado'] != null) { //alumno
                log('entro else if 3');
                $per_id_alumno = Persona::create([
                    'per_dni' => $newRequest['per_dni_Alumno'],
                    'per_nombres' => $newRequest['per_nombres_Alumno'],
                    'per_apellidos' => $newRequest['per_apellidos_Alumno'],
                    'per_nombre_completo' => $newRequest['per_nombres_Alumno'] . ' ' . $newRequest['per_apellidos_Alumno'],
                    'per_email' => $newRequest['per_email_Alumno'],
                    'per_sexo' => $newRequest['per_sexo_Alumno'],
                    'per_fecha_nacimiento' => $newRequest['per_fecha_nacimiento_Alumno'],
                    'per_estado_civil' => $newRequest['per_estado_civil_Alumno'],
                    'per_celular' => $newRequest['per_celular_Alumno'],
                    'per_pais' => $newRequest['per_pais_Alumno'],
                    'per_departamento' => $newRequest['per_departamento_Alumno'],
                    'per_provincia' => $newRequest['per_provincia_Alumno'],
                    'per_distrito' => $newRequest['per_distrito_Alumno'],
                    'per_direccion' => $newRequest['per_direccion_Alumno'],
                ]);
                $perAlumnoUser = User::create([
                    'per_id' => $per_id_alumno->per_id,
                    'email' => $newRequest['per_email_Alumno'],
                    'password' => bcrypt($newRequest['per_dni_Alumno']),
                    'name' => $per_id_alumno->per_nombre_completo,
                    'rol_id' => 1
                ]);
                $perAlumnoUser->assignRole('Alumno');


                $newRequest['per_id_Alumno'] = $per_id_alumno->per_id;

                $Apoderado = Apoderado::create([
                    'per_id' => $newRequest['per_id_Apoderado'],
                    'apo_parentesco' => $newRequest['per_parentesco_Apoderado'],
                    'apo_vive_con_estudiante' => $newRequest['per_vive_con_estudiante_Apoderado'],
                ]);
            } else {
                log('entro else');
                $per_id_Apoderado = Persona::create([
                    'per_dni' => $newRequest['per_dni_Apoderado'],
                    'per_nombres' => $newRequest['per_nombres_Apoderado'],
                    'per_apellidos' => $newRequest['per_apellidos_Apoderado'],
                    'per_nombre_completo' => $newRequest['per_nombres_Apoderado'] . ' ' . $newRequest['per_apellidos_Apoderado'],
                    'per_email' => $newRequest['per_email_Apoderado'],
                    'per_sexo' => $newRequest['per_sexo_Apoderado'],
                    'per_fecha_nacimiento' => $newRequest['per_fecha_nacimiento_Apoderado'],
                    'per_estado_civil' => $newRequest['per_estado_civil_Apoderado'],
                    'per_celular' => $newRequest['per_celular_Apoderado'],
                    'per_pais' => $newRequest['per_pais_Apoderado'],
                    'per_departamento' => $newRequest['per_departamento_Apoderado'],
                    'per_provincia' => $newRequest['per_provincia_Apoderado'],
                    'per_distrito' => $newRequest['per_distrito_Apoderado'],
                    'per_direccion' => $newRequest['per_direccion_Apoderado']
                ]);
                $newRequest['per_id_Alumno'] = $per_id_Apoderado->per_id;
                $perUser = User::create([
                    'per_id' => $per_id_Apoderado->per_id,
                    'email' => $newRequest['per_email_Apoderado'],
                    'password' => bcrypt($newRequest['per_dni_Apoderado']),
                    'name' => $per_id_Apoderado->per_nombre_completo,
                    'rol_id' => 1
                ]);
                $perUser->assignRole('Apoderado');



                $per_id_alumno = Persona::create([
                    'per_dni' => $newRequest['per_dni_Alumno'],
                    'per_nombres' => $newRequest['per_nombres_Alumno'],
                    'per_apellidos' => $newRequest['per_apellidos_Alumno'],
                    'per_nombre_completo' => $newRequest['per_nombres_Alumno'] . ' ' . $newRequest['per_apellidos_Alumno'],
                    'per_email' => $newRequest['per_email_Alumno'],
                    'per_sexo' => $newRequest['per_sexo_Alumno'],
                    'per_fecha_nacimiento' => $newRequest['per_fecha_nacimiento_Alumno'],
                    'per_estado_civil' => $newRequest['per_estado_civil_Alumno'],
                    'per_celular' => $newRequest['per_celular_Alumno'],
                    'per_pais' => $newRequest['per_pais_Alumno'],
                    'per_departamento' => $newRequest['per_departamento_Alumno'],
                    'per_provincia' => $newRequest['per_provincia_Alumno'],
                    'per_distrito' => $newRequest['per_distrito_Alumno'],
                    'per_direccion' => $newRequest['per_direccion_Alumno']
                ]);

                $perAlumnoUser = User::create([
                    'per_id' => $per_id_alumno->per_id,
                    'email' => $newRequest['per_email_Alumno'],
                    'password' => bcrypt($newRequest['per_dni_Alumno']),
                    'name' => $per_id_alumno->per_nombre_completo,
                    'rol_id' => 1
                ]);
                $perAlumnoUser->assignRole('Alumno');
                $newRequest['per_id_Alumno'] = $per_id_alumno->per_id;


                $Apoderado = Apoderado::create([
                    'per_id' => $per_id_Apoderado->per_id,
                    'apo_parentesco' => $newRequest['per_parentesco_Apoderado'],
                    'apo_vive_con_estudiante' => $newRequest['per_vive_con_estudiante_Apoderado'],
                ]);
            }
            log('salio');

            Alumno::create([
                'per_id' => $newRequest['per_id_Alumno'],
                'apo_id' => $Apoderado ? $Apoderado->apo_id : null,
                'alu_estado' => 1
            ]);
        });
        return redirect()->route('alumno.inicio')->with('success', 'Alumno registrado correctamente');
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
        $sexo = $this->sexo;
        $estadoCivil = $this->estadoCivil;
        $vive = $this->vive;
        $parentesco = $this->parentesco;
        $departamentos = Departamento::all();

        return view('view.alumno.edit', compact('sexo', 'estadoCivil', 'vive', 'parentesco', 'departamentos', 'alumno'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alumno $alumno)
    {
        $newRequest = $request->all();

        DB::transaction(function () use ($newRequest, $alumno) {
            $alumno->persona->update([
                'per_dni' => $newRequest['per_dni_Alumno'],
                'per_nombres' => $newRequest['per_nombres_Alumno'],
                'per_apellidos' => $newRequest['per_apellidos_Alumno'],
                'per_email' => $newRequest['per_email_Alumno'],
                'per_sexo' => $newRequest['per_sexo_Alumno'],
                'per_fecha_nacimiento' => $newRequest['per_fecha_nacimiento_Alumno'],
                'per_estado_civil' => $newRequest['per_estado_civil_Alumno'],
                'per_celular' => $newRequest['per_celular_Alumno'],
                'per_pais' => $newRequest['per_pais_Alumno'],
                'per_departamento' => $newRequest['per_departamento_Alumno'],
                'per_provincia' => $newRequest['per_provincia_Alumno'],
                'per_distrito' => $newRequest['per_distrito_Alumno'],
                'per_direccion' => $newRequest['per_direccion_Alumno'],
            ]);

            $alumno->apoderado->persona->update([
                'per_dni' => $newRequest['per_dni_Apoderado'],
                'per_nombres' => $newRequest['per_nombres_Apoderado'],
                'per_apellidos' => $newRequest['per_apellidos_Apoderado'],
                'per_email' => $newRequest['per_email_Apoderado'],
                'per_sexo' => $newRequest['per_sexo_Apoderado'],
                'per_fecha_nacimiento' => $newRequest['per_fecha_nacimiento_Apoderado'],
                'per_estado_civil' => $newRequest['per_estado_civil_Apoderado'],
                'per_celular' => $newRequest['per_celular_Apoderado'],
                'per_pais' => $newRequest['per_pais_Apoderado'],
                'per_departamento' => $newRequest['per_departamento_Apoderado'],
                'per_provincia' => $newRequest['per_provincia_Apoderado'],
                'per_distrito' => $newRequest['per_distrito_Apoderado'],
                'per_direccion' => $newRequest['per_direccion_Apoderado'],
            ]);

            $alumno->apoderado->update([
                'apo_parentesco' => $newRequest['per_parentesco_Apoderado'],
                'apo_vive_con_estudiante' => $newRequest['per_vive_con_estudiante_Apoderado'],
            ]);
        });

        return redirect()->route('alumno.inicio')->with('success', 'Alumno actualizado correctamente');
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
