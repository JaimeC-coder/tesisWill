<?php

namespace App\Http\Controllers;

use App\Helpers\WhatsAppHelper;
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
        $data = $request->all();
        Log::info($data);
        // Validación previa por DNI
        $dniAlumno = $data['per_dni_Alumno'] ?? null;
        $dniApoderado = $data['per_dni_Apoderado'] ?? null;

        $alumnoExistente = Persona::where('per_dni', $dniAlumno)->first();
        $apoderadoExistente = Persona::where('per_dni', $dniApoderado)->first();

        // Si ya existen ambos, redireccionar directamente
        if ($alumnoExistente && $apoderadoExistente) {
            WhatsAppHelper::enviarMensaje("Alumno y Apoderado ya existen, redirigiendo...");
            return redirect()->route('alumno.inicio')->with('info', 'El alumno y el apoderado ya están registrados.');
        }
        DB::transaction(function () use ($data) {
            $alumnoId = $data['per_id_Alumno'] ?? null;
            $apoderadoId = $data['per_id_Apoderado'] ?? null;

            // ✅ Actualizar datos si ya existen
            if ($alumnoId) {
                WhatsAppHelper::enviarMensaje("metodo if alumnoId 1 existe");
                $this->updatePersona($alumnoId, $data, 'Alumno');
            }

            if ($apoderadoId) {
                WhatsAppHelper::enviarMensaje("metodo if apoderadoId 1 existe");
                $this->updatePersona($apoderadoId, $data, 'Apoderado');
            }

            // 🎯 Crear Persona si no existen
            if (!$alumnoId) {
                WhatsAppHelper::enviarMensaje("metodo if alumnoId 2 si no existe existe");
                $alumno = $this->createPersona($data, 'Alumno');
                $alumnoId = $alumno->per_id;
                $this->createUser($alumno, 'Alumno');
            }

            if (!$apoderadoId) {
                WhatsAppHelper::enviarMensaje("metodo if apoderadoId 2  si no existe existe");
                $apoderado = $this->createPersona($data, 'Apoderado');
                $apoderadoId = $apoderado->per_id;
                $this->createUser($apoderado, 'Apoderado');
            } else {
                WhatsAppHelper::enviarMensaje("metodo if apoderadoId 2  else");
                $apoderado = Apoderado::where('per_id', $apoderadoId)->first();
            }

            // 🏷️ Crear/Actualizar Apoderado
            if (!$apoderado->apo_id) {
                Log::info("entro ");
                WhatsAppHelper::enviarMensaje("metodo if apoderado   si no existe existe");
                $apoderado = Apoderado::create([
                    'per_id' => $apoderadoId,
                    'apo_parentesco' => $data['per_parentesco_Apoderado'],
                    'apo_vive_con_estudiante' => $data['per_vive_con_estudiante_Apoderado'],
                ]);
            }


            // 👦 Crear Alumno
            WhatsAppHelper::enviarMensaje("registra al estudiante");
            Alumno::create([
                'per_id' => $alumnoId,
                'apo_id' => $apoderado->apo_id,
                'alu_estado' => 1
            ]);
        });

        return redirect()->route('alumno.inicio')->with('success', 'Alumno registrado correctamente');
    }

    private function updatePersona($id, $data, $tipo)
    {
        $persona = Persona::find($id);
        if (!$persona) return;

        $prefix = strtolower($tipo);
        $persona->update(array_filter([
            'per_dni' => $data["per_dni_{$tipo}"] ?? null,
            'per_nombres' => $data["per_nombres_{$tipo}"] ?? null,
            'per_apellidos' => $data["per_apellidos_{$tipo}"] ?? null,
            'per_nombre_completo' => isset($data["per_nombres_{$tipo}"], $data["per_apellidos_{$tipo}"]) ?
                $data["per_nombres_{$tipo}"] . ' ' . $data["per_apellidos_{$tipo}"] : null,
            'per_email' => $data["per_email_{$tipo}"] ?? null,
            'per_sexo' => $data["per_sexo_{$tipo}"] ?? null,
            'per_fecha_nacimiento' => $data["per_fecha_nacimiento_{$tipo}"] ?? null,
            'per_estado_civil' => $data["per_estado_civil_{$tipo}"] ?? null,
            'per_celular' => $data["per_celular_{$tipo}"] ?? null,
            'per_pais' => $data["per_pais_{$tipo}"] ?? null,
            'per_departamento' => $data["per_departamento_{$tipo}"] ?? null,
            'per_provincia' => $data["per_provincia_{$tipo}"] ?? null,
            'per_distrito' => $data["per_distrito_{$tipo}"] ?? null,
            'per_direccion' => $data["per_direccion_{$tipo}"] ?? null,
        ], fn($val) => !is_null($val) && $val !== ''));
    }

    private function createPersona($data, $tipo)
    {
        return Persona::create([
            'per_dni' => $data["per_dni_{$tipo}"],
            'per_nombres' => $data["per_nombres_{$tipo}"],
            'per_apellidos' => $data["per_apellidos_{$tipo}"],
            'per_nombre_completo' => $data["per_nombres_{$tipo}"] . ' ' . $data["per_apellidos_{$tipo}"],
            'per_email' => $data["per_email_{$tipo}"],
            'per_sexo' => $data["per_sexo_{$tipo}"],
            'per_fecha_nacimiento' => $data["per_fecha_nacimiento_{$tipo}"],
            'per_estado_civil' => $data["per_estado_civil_{$tipo}"],
            'per_celular' => $data["per_celular_{$tipo}"],
            'per_pais' => $data["per_pais_{$tipo}"],
            'per_departamento' => $data["per_departamento_{$tipo}"],
            'per_provincia' => $data["per_provincia_{$tipo}"],
            'per_distrito' => $data["per_distrito_{$tipo}"],
            'per_direccion' => $data["per_direccion_{$tipo}"],
        ]);
    }

    private function createUser($persona, $rol)
    {
        $user = User::create([
            'per_id' => $persona->per_id,
            'email' => $persona->per_email,
            'password' => bcrypt($persona->per_dni),
            'name' => $persona->per_nombre_completo,
            'rol_id' => 1
        ]);
        $user->assignRole($rol);
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
