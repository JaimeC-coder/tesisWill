<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Grado;
use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CursoController extends Controller
{
    public $nivel ;
    public $grado ;
    public $estado = [1 => 'Activo', 2 => 'Inactivo'];
    /**
     * Display a listing of the resource.
     */


     public function __construct()
     {
         $this->nivel = Nivel::all();
         $this->grado = Grado::where('gra_is_delete', '!=', 1)->get();
     }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $curso = new Curso();
        $niveles = $this->nivel;
        $grados = $this->grado;
        $estados = $this->estado;
        return view('view.curso.create', compact('curso', 'niveles', 'grados', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $newRequest = $request->all();

        DB::transaction(function () use ($newRequest) {
            if ($newRequest['grado_id'] == -1) {
                $grados = Grado::where('gra_is_delete', '!=', 1)->get();
                foreach ($grados as $grado) {
                    $curso = Curso::create([
                        'cur_nombre' => $newRequest['curso'],
                        'cur_abreviatura' => $newRequest['abreviatura'],
                        'cur_horas' => $newRequest['horas'],
                        'gra_id' => $grado->gra_id,
                        'niv_id' => $newRequest['nivel_id'],
                        'per_id' => null,
                        'cur_estado' => 1,
                        'is_deleted' => 0
                    ]);

                    $capacidades = $newRequest['capacidades'];
                    $capacidades = str_replace('[', '', $capacidades);
                    $capacidades = str_replace(']', '', $capacidades);
                    $capacidades = str_replace('"', '', $capacidades);
                    $capacidades = explode(',', $capacidades);
                    foreach ($capacidades as $capacidad) {
                        $curso->capacidad()->create([
                            'cap_descripcion' => $capacidad,
                            'cap_is_deleted' => 0
                        ]);
                    }
                }

            } else {
                $curso = Curso::create([
                    'cur_nombre' => $newRequest['curso'],
                    'cur_abreviatura' => $newRequest['abreviatura'],
                    'cur_horas' => $newRequest['horas'],
                    'gra_id' => $newRequest['grado_id'],
                    'niv_id' => $newRequest['nivel_id'],
                    'per_id' => null,
                    'cur_estado' => 1,
                    'is_deleted' => 0
                ]);

                $capacidades = $newRequest['capacidades'];
                $capacidades = str_replace('[', '', $capacidades);
                $capacidades = str_replace(']', '', $capacidades);
                $capacidades = str_replace('"', '', $capacidades);
                $capacidades = explode(',', $capacidades);
                foreach ($capacidades as $capacidad) {
                    $curso->capacidad()->create([
                        'cap_descripcion' => $capacidad,
                        'cap_is_deleted' => 0
                    ]);
                }
            }

        });
        return redirect()->route('curso.inicio')->with('success', 'Alumno registrado correctamente');


    }

    /**
     * Display the specified resource.
     */
    public function show(Curso $curso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curso $curso)
    {
        $niveles = $this->nivel;
        $grados = $this->grado;
        $estados = $this->estado;
        return view('view.curso.edit', compact('curso', 'niveles', 'grados', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Curso $curso)
    {
        $curso->update([
            'cur_nombre' => $request['curso'],
            'cur_abreviatura' => $request['abreviatura'],
            'cur_horas' => $request['horas'],
            'gra_id' => $request['grado_id'],
            'niv_id' => $request['nivel_id'],
            'cur_estado' => $request['estado']
        ]);
        $curso->capacidad()->delete();
        $capacidades = $request['capacidades'];
        $capacidades = str_replace('[', '', $capacidades);
        $capacidades = str_replace(']', '', $capacidades);
        // quitar el "" de las capacidades
        $capacidades = str_replace('"', '', $capacidades);

        $capacidades = explode(',', $capacidades);
        foreach ($capacidades as $capacidad) {
            $curso->capacidad()->create([
                'cap_descripcion' => $capacidad,
                'cap_is_deleted' => 0
            ]);
        }

        return redirect()->route('curso.inicio')->with('success', 'Año escolar actualizado correctamente');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curso $curso)
    {
        DB::beginTransaction();
        try {
            $curso->update([
                'is_deleted' => 1
            ]);

            DB::commit();

            return redirect()->route('curso.inicio')->with('success', 'Año escolar eliminado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('curso.inicio')->with('error', 'Error al eliminar el año escolar');
        }
    }

    public function inicio()
    {
        $cursos = Curso::where('is_deleted','!=',1)->orderBy('cur_id', 'desc')->get();
        //return $cursos[0]->capacidad;

        return view('view.curso.inicio', compact('cursos'));
    }
}
