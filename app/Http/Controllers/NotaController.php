<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Anio;
use App\Models\AsignarCurso;
use App\Models\AsignarGrado;
use App\Models\Capacidad;
use App\Models\Curso;
use App\Models\Grado;
use App\Models\Gsa;
use App\Models\Matricula;
use App\Models\Nivel;
use App\Models\NotaCapacidad;
use App\Models\Periodo;
use App\Models\Persona;
use App\Models\PersonalAcademico;
use App\Models\Seccion;
use App\Models\Tipo;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class NotaController extends Controller
{
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Nota $nota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nota $nota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nota $nota)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nota $nota)
    {
        //
    }
    public function inicio(Request $request, $nivels = null)
    {

        $anios = Anio::where('is_deleted', '!=', 1)->get();
        log($request);
        $nivel = $request->nivel;
        $anio = $request->anio;
        $docente = $request->docente;
        $grado = $request->grado;
        $seccion = $request->seccion;
        $cursoId = $request->cursoId;

        $curso = Curso::where('gra_id', $grado)->where('niv_id', $nivel)->where('cur_id', $cursoId)->where('is_deleted', '!=', 1)->first();

        if ($curso != null) {
            $asignacionesCursos = AsignarCurso::where('pa_id', $docente)->where('curso', $curso->cur_nombre)->where('asig_is_deleted', '!=', 1)->first();

            $capacidades = Capacidad::where('cur_id', $curso->cur_id)->where('cap_is_deleted', '!=', 1)->get();
            $asignacionesCursos->capacidades = count($capacidades);

            $estado = 1;
            $anio = Anio::where('anio_estado', $estado)->where('is_deleted', '!=', 1)->first();
            $periodo = Periodo::where('anio_id', $anio->anio_id)->where('per_estado', $estado)->where('is_deleted', '!=', 1)->first();
            $tipoPeriodo = Tipo::where('tp_id', $periodo->per_tp_notas)->first();
            Log::info($tipoPeriodo);

            switch ($tipoPeriodo->tp_tipo) {
                case 'Registro de Notas Anuales':
                    $tipoPeriodo->cantidad = 1;
                    $tipoPeriodo->name = 'Anual';
                    break;
                case 'Registro de Notas Bimestrales':
                    $tipoPeriodo->cantidad = 4;
                    $tipoPeriodo->name = 'Bimestre';
                    break;
                case 'Registro de Notas Trimestrales':
                    $tipoPeriodo->cantidad = 3;
                    $tipoPeriodo->name = 'Trimestre';
                    break;
                case 'Registro de Notas Semestrales':
                    $tipoPeriodo->cantidad = 2;
                    $tipoPeriodo->name = 'Semestre';
                    break;
            }

            $Gsas = Gsa::where('niv_id', $nivel)->where('gra_id', $grado)->where('sec_id', $seccion)->where('is_deleted', '!=', 1)->get();

            foreach ($Gsas as $g) {
                $matricula = Matricula::where('ags_id', $g->ags_id)->where('is_deleted', '!=', 1)->first();
                if ($matricula !== null) {
                    $alumno = Alumno::where('alu_id', $matricula->alu_id)->where('is_deleted', '!=', 1)->first();
                    $persona = Persona::where('per_id', $alumno->per_id)->where('is_deleted', '!=', 1)->first();
                    $g->alumno = $persona->per_apellidos . ' ' . $persona->per_nombres;
                    $g->dni = $persona->per_dni;
                    $g->idAlumno = $alumno->alu_id;

                    $notas = Nota::where('alu_id', $alumno->alu_id)->where('curso_id', $curso->cur_id)->where('pa_id', $docente)->get();
                    $suma = 0;
                    $total = $tipoPeriodo->cantidad;

                    foreach ($notas as $v) {
                        $capacidades = NotaCapacidad::where('nt_id', $v->nt_id)->get();
                        $v->notasCapacidades = $capacidades;

                        // Agregar valor de notaValor
                        $v->notaValor = $this->buscarInfoNotas($v->nt_nota);

                        $suma += $v->nt_nota;
                    }

                    $g->notas = $notas;
                    $g->promedio = $suma / $total;
                    $g->promedioValor = $this->buscarInfoNotas($g->promedio);
                }
            }

            Log::info($Gsas);

            return view('view.notas.inicio', compact('anios', 'asignacionesCursos', 'Gsas', 'tipoPeriodo'));
        }

        $asignacionesCursos = null;
        $Gsas = null;
        $tipoPeriodo = null;
        return view('view.notas.inicio', compact('anios', 'asignacionesCursos', 'Gsas', 'tipoPeriodo'));
    }





    public function buscarDocente(Request $request)
    {
        $nivel = $request->nivel;
        $docentes = PersonalAcademico::whereIn('rol_id', [4])->where('niv_id', $nivel)->where('is_deleted', '!=', 1)->get();
        foreach ($docentes as $d) {
            $persona = Persona::where('per_id', $d->per_id)->first();
            $d->dni = $persona->per_dni;
            $d->nombres = $persona->per_nombres;
            $d->apellidos = $persona->per_apellidos;
        }
        return response()->json([
            'docente' => $docentes,
            'status' => 200,

        ]);
    }

    public function buscarGrados(Request $request)
    {
        $docente = $request->docente;

        $docentes = PersonalAcademico::where('pa_id', $docente)->where('is_deleted', '!=', 1)->first();
        $asignacionesCursos = AsignarCurso::where('pa_id', $docentes->pa_id)->where('asig_is_deleted', '!=', 1)->first();
        $asignacionesGrados = AsignarGrado::where('pa_id', $docentes->pa_id)->where('asig_is_deleted', '!=', 1)->get();
        $array = [];
        foreach ($asignacionesGrados as $value) {
            if (in_array($value->gra_id, $array) == false) {
                array_push($array, $value->gra_id);
            }
        }
        $grados = Grado::whereIn('gra_id', $array)->where('gra_is_delete', '!=', 1)->get();
        $docentes->curso = $asignacionesCursos->curso;
        $docentes->grados = $array;

        return response()->json([
            'docente' => $docentes,
            'grados' => $grados,
        ]);
    }

    public function buscarSeccion(Request $request)
    {
        $docente = $request['params']['docente'];
        $grado = $request['params']['grado'];

        $asignacionesGrados = AsignarGrado::where('pa_id', $docente)->where('gra_id', $grado)->where('asig_is_deleted', '!=', 1)->get();
        $array = [];
        foreach ($asignacionesGrados as $value) {
            if (in_array($value->gra_id, $array) == false) {
                array_push($array, $value->seccion);
            }
        }
        $secciones = Seccion::whereIn('sec_descripcion', $array)->where('gra_id', $grado)->where('sec_is_delete', '!=', 1)->get();

        return response()->json([
            'secciones' => $secciones
        ]);
    }

    public function getCoursesByTeacher(Request $request)
    {
        $teacherId = $request->teacherId;
        $nivelId = $request->nivelId;
        $gradoId = $request->grado_id;
        $cursos = Curso::join('asignar_cursos', 'cursos.cur_nombre', '=', 'asignar_cursos.curso')
            ->where('asignar_cursos.pa_id', $teacherId)
            ->where('cursos.gra_id', $gradoId)
            ->where('cursos.niv_id',  $nivelId)
            ->where('asignar_cursos.asig_is_deleted', 0)
            ->select('cursos.cur_id', 'cursos.cur_nombre')
            ->get();
        return response()->json([
            'status' => 200,
            'cursos' => $cursos
        ]);
    }
    public function buscarInfoNotas($numero)
    {
        $valor = "";
        if ($numero <= 11) {
            $valor = "C";
        }
        if ($numero >= 12 && $numero <= 14) {
            $valor = "B";
        }
        if ($numero >= 15 && $numero <= 20) {
            $valor = "A";
        }
        return $valor;
    }
}
