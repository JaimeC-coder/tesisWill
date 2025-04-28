<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Nivel;
use App\Models\Persona;
use App\Models\PersonalAcademico;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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


        return view('view.personalAcademico.create', compact('personal', 'sexo', 'estadoCivil', 'tutor', 'niveles', 'roles','departamentos'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        DB::beginTransaction();
        try {
            if($request->flexCheckDefault == 'on'){
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
            }else{

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

        return view('view.personalAcademico.edit', compact('personal','departamentos', 'sexo', 'estadoCivil', 'tutor', 'niveles', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PersonalAcademico $personal )
    {
        DB::beginTransaction();
        try {
            if($request->per_id){
                $personal->update([
                    'per_id'=>$request->per_id,
                    'pa_turno'=>$request->pa_turno,
                    'pa_condicion_laboral' => $request->pa_condicion_laboral,
                    'niv_id' => $request->niv_id,
                    'pa_especialidad' => $request->pa_especialidad,
                    'rol_id' => $request->rol_id,
                    'pa_is_tutor' => $request->pa_is_tutor,
                ]);

            }else{
                $personal->persona->update([
                    'per_nombres' => $request->per_nombres,
                    'per_apellidos' => $request->per_apellidos,
                    'per_sexo' => $request->per_sexo,
                    'per_direccion' => $request->per_direccion,
                    'per_telefono' => $request->per_telefono,
                    'per_celular' => $request->per_celular,
                    'per_email' => $request->per_email,
                    'per_estado_civil' => $request->per_estado_civil,
                    'per_tutor' => $request->per_tutor,
                    'per_nivel_id' => $request->per_nivel_id,
                    'per_rol_id' => $request->per_rol_id,
                    'per_departamento_id' => $request->per_departamento_id,
                ]);
            }

            DB::commit();

            return redirect()->route('personal.inicio')->with('success', 'Año escolar creado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('personal.edit')->with('error', 'Error al crear el aula');
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
        $personal = PersonalAcademico::where('is_deleted','!=',1)->orderBy('pa_id', 'desc')->get();

        return view('view.personalAcademico.inicio', compact('personal'));
    }
}
