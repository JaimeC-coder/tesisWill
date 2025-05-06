<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Nivel;
use App\Models\Persona;
use App\Models\PersonalAcademico;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class PersonalAcademicoController extends Controller
{
    public $sexo = ['Masculino' => 'Masculino', 'Femenino' => 'Femenino'];
    public $estadoCivil = ['Soltero' => 'Soltero', 'Casado' => 'Casado', 'Divorciado' => 'Divorciado', 'Viudo' => 'Viudo'];
    public $tutor = [1 => 'Si', 2 => 'No'];

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
        $personal = new PersonalAcademico();
        $sexo = $this->sexo;
        $estadoCivil = $this->estadoCivil;
        $tutor = $this->tutor;
        $departamentos = Departamento::all();

        $niveles = Nivel::all();
        $roles = Rol::where('is_deleted', '!=', 1)->get();


        return view('view.personalAcademico.create', compact('personal', 'sexo', 'estadoCivil', 'tutor', 'niveles', 'roles', 'departamentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      
        DB::beginTransaction();
        try {
            if (isset($request->flexCheckDefault ) && $request->flexCheckDefault == 'on' || $request->per_id == null) {
                $persona = Persona::create([
                    'per_dni' => $request->per_dni,
                    'per_nombres' => $request->per_nombres,
                    'per_apellidos' => $request->per_apellidos,
                    'per_nombre_completo' => $request->per_nombres . ' ' . $request->per_apellidos,
                    'per_email' => $request->per_email,
                    'per_fecha_nacimiento' => $request->per_fecha_nacimiento,
                    'per_direccion' => $request->per_direccion,
                    'per_sexo' => $request->per_sexo,
                    'per_estado_civil' => $request->per_estado_civil,
                    'per_celular' => $request->per_celular,
                    'per_pais' => $request->per_pais,
                    'per_departamento' => $request->per_departamento,
                    'per_provincia' => $request->per_provincia,
                    'per_distrito' => $request->per_distrito,
                ]);
                PersonalAcademico::create([
                    'per_id' => $persona->per_id,
                    'pa_turno' => $request->pa_turno,
                    'pa_condicion_laboral' => $request->pa_condicion_laboral,
                    'niv_id' => $request->niv_id,
                    'pa_especialidad' => $request->pa_especialidad,
                    'rol_id' => $request->rol_id,
                    'pa_is_tutor' => $request->pa_is_tutor,
                ]);
            } else {

                PersonalAcademico::create([
                    'per_id' => $request->per_id,
                    'pa_turno' => $request->pa_turno,
                    'pa_condicion_laboral' => $request->pa_condicion_laboral,
                    'niv_id' => $request->niv_id,
                    'pa_especialidad' => $request->pa_especialidad,
                    'rol_id' => $request->rol_id,
                    'pa_is_tutor' => $request->pa_is_tutor,
                ]);
            }


            DB::commit();
            return redirect()->route('personal.inicio')->with('success', 'Personal academico creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('personal.create')->with('error', 'Error al crear el personal academico');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PersonalAcademico $personalAcademico)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PersonalAcademico $personal)
    {


        $sexo = $this->sexo;
        $estadoCivil = $this->estadoCivil;
        $tutor = $this->tutor;
        $departamentos = Departamento::all();
        $niveles = Nivel::all();
        $roles = Rol::where('is_deleted', '!=', 1)->get();

        return view('view.personalAcademico.edit', compact('personal', 'departamentos', 'sexo', 'estadoCivil', 'tutor', 'niveles', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PersonalAcademico $personal)
    {
        Log::info($request->all());
        DB::beginTransaction();
        try {
            if ($request->per_id) {



                $personal->update(array_filter([

                    'per_id' => $request->per_id ?? null,
                    'pa_turno' => $request->pa_turno ?? null,
                    'pa_condicion_laboral' => $request->pa_condicion_laboral ?? null,
                    'niv_id' => $request->niv_id ?? null,
                    'pa_especialidad' => $request->pa_especialidad ?? null,
                    'rol_id' => $request->rol_id ?? null,
                    'pa_is_tutor' => $request->pa_is_tutor ?? null,
                ], fn($value) => !is_null($value) && $value !== ''));
                $personal->persona->update(array_filter([
                    'per_dni' => $request->per_dni ?? null,
                    'per_nombres' => $request->per_nombres ?? null,
                    'per_apellidos' => $request->per_apellidos ?? null,
                    'per_nombre_completo' => $request->per_nombres . ' ' . $request->per_apellidos,
                    'per_email' => $request->per_email ?? null,
                    'per_fecha_nacimiento' => $request->per_fecha_nacimiento ?? null,
                    'per_direccion' => $request->per_direccion ?? null,
                    'per_sexo' => $request->per_sexo ?? null,
                    'per_estado_civil' => $request->per_estado_civil ?? null,
                    'per_celular' => $request->per_celular ?? null,
                    'per_pais' => $request->per_pais ?? null,
                    'per_departamento' => $request->per_departamento ?? null,
                    'per_provincia' => $request->per_provincia ?? null,
                    'per_distrito' => $request->per_distrito ?? null,

                ], fn($value) => !is_null($value) && $value !== ''));

            } else {
                return redirect()->route('personal.edit')->with('error', 'Error al actualizar el personal academico');
            }


            DB::commit();

            return redirect()->route('personal.inicio')->with('success', 'Personal academico actualizado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar el personal academico: ' . $e->getMessage());
            return redirect()->route('personal.edit')->with('error', 'Error al actualizar el personal academico');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonalAcademico $personal)
    {

        DB::beginTransaction();
        try {
            $personal->update([
                'is_deleted' => 1
            ]);
            DB::commit();
            return redirect()->route('personal.inicio')->with('success', 'Personal academico eliminado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('personal.inicio')->with('error', 'Error al eliminar el personal academico');
        }
    }
    public function inicio()
    {
        $personal = PersonalAcademico::where('is_deleted', '!=', 1)->orderBy('pa_id', 'desc')->get();

        return view('view.personalAcademico.inicio', compact('personal'));
    }
}
