<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public $sexo = ['M' => 'Masculino', 'F' => 'Femenino'];
    public $estadoCivil = ['S' => 'Soltero', 'C' => 'Casado', 'D' => 'Divorciado', 'V' => 'Viudo'];
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
        return $request;
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

        return view('view.alumno.edit',compact('sexo','estadoCivil','vive','parentesco','departamentos','alumno'));



    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alumno $alumno)
    {
        //

        return $request;
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
