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
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        $user = $user->per_id;
        $anios = Anio::where('anio_estado', '!=', 1)->get();

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


            $capacidad = $this->returnCapacitaciones($capacidades);
            $estado = 1;
            $anio = Anio::where('anio_estado', $estado)->where('is_deleted', '!=', 1)->first();
            $periodo = Periodo::where('anio_id', $anio->anio_id)->where('per_estado', $estado)->where('is_deleted', '!=', 1)->first();
            $tipoPeriodo = Tipo::where('tp_id', $periodo->per_tp_notas)->first();

            $asignacionesCursos->capacidades = count($capacidades);



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

            $Gsas = $this->getGsas($nivel, $grado, $seccion, $curso, $docente, $tipoPeriodo);


            return view('view.notas.inicio', compact('anios', 'asignacionesCursos', 'Gsas', 'tipoPeriodo', 'capacidad', 'user'));
        }

        $asignacionesCursos = null;
        $Gsas = null;
        $tipoPeriodo = null;
        return view('view.notas.inicio', compact('anios', 'asignacionesCursos', 'Gsas', 'tipoPeriodo', 'user'));
    }





    public function buscarDocente(Request $request)
    {

        Log::info($request);

        $nivel = $request->nivel;
        $user = User::Where('per_id', $request->user)
        ->where('is_deleted', '!=', 1)
        ->first();

        if ($user->roles[0]->name == "Docente") {
            $docentes = PersonalAcademico::where('per_id', $user->per_id)->where('is_deleted', '!=', 1)->get();

        } else {
            $docentes = PersonalAcademico::whereIn('rol_id', [4])->where('niv_id', $nivel)->where('is_deleted', '!=', 1)->get();
        }

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


    //PASAR A API :
    public function inicioaPI(Request $request, $nivels = null)
    {

        $anios = Anio::where('anio_estado', '!=', 1)->get();
        // log($request);
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
            // $asignacionesCursos->capacidades = $capacidades;
            $asignacionesCursos->capacidades = count($capacidades);
            $capacidad = $this->returnCapacitaciones($capacidades);
            $estado = 1;
            $anio = Anio::where('anio_estado', $estado)->where('is_deleted', '!=', 1)->first();
            $periodo = Periodo::where('anio_id', $anio->anio_id)->where('per_estado', $estado)->where('is_deleted', '!=', 1)->first();
            $tipoPeriodo = Tipo::where('tp_id', $periodo->per_tp_notas)->first();
            //Log::info($tipoPeriodo);

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

            $Gsas = $this->getGsas($nivel, $grado, $seccion, $curso, $docente, $tipoPeriodo);

            //  Log::info($Gsas);

            return response()->json([
                // 'anios' => $anios,
                // 'asignacionesCursos' => $asignacionesCursos,
                'Gsas' => $Gsas,
                // 'tipoPeriodo' => $tipoPeriodo,
                // 'capacidades' => $capacidad
            ]);
        }

        $asignacionesCursos = null;
        $Gsas = null;
        $tipoPeriodo = null;
        return response()->json([
            // 'anios' => $anios,
            //  'asignacionesCursos' => $asignacionesCursos,
            'Gsas' => $Gsas,
            // 'tipoPeriodo' => $tipoPeriodo
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





    public function returnCapacitaciones($capacitacion)
    {

        $capacidades = [];

        for ($i = 0; $i < count($capacitacion); $i++) {
            $capacidades["C" . $i + 1] = $capacitacion[$i]['cap_descripcion'];
        }

        return $capacidades;
    }


    public function getGsas($nivel, $grado, $seccion, $curso, $docente, $tipoPeriodo)
    {
        $Gsas = Gsa::select('ags_id')
            ->where('niv_id', $nivel)
            ->where('gra_id', $grado)
            ->where('sec_id', $seccion)
            ->where('is_deleted', '!=', 1)
            ->get();


        $numeroPeriodos =  $tipoPeriodo->tp_id;

        foreach ($Gsas as $g) {
            $matricula = Matricula::where('ags_id', $g->ags_id)
                ->where('is_deleted', '!=', 1)
                ->first();

            if (!$matricula) continue;

            $alumno = Alumno::where('alu_id', $matricula->alu_id)
                ->where('is_deleted', '!=', 1)
                ->first();

            if (!$alumno) continue;

            $persona = Persona::where('per_id', $alumno->per_id)
                ->where('is_deleted', '!=', 1)
                ->first();

            if (!$persona) continue;

            $g->periodoID = $matricula->per_id;
            $g->alumno = $persona->per_apellidos . ' ' . $persona->per_nombres;
            $g->dni = $persona->per_dni;
            $g->idAlumno = $alumno->alu_id;

            $notas = Nota::select(['nt_bimestre', 'nt_nota', 'nt_id'])
                ->where('alu_id', $alumno->alu_id)
                ->where('curso_id', $curso->cur_id)
                ->where('pa_id', $docente)
                ->where('nt_is_deleted', '!=', 1)
                ->get();

            $notasOrganizadas = [];

            foreach ($notas as $v) {
                $capacidades = NotaCapacidad::select(['nc_descripcion', 'nc_nota', 'nt_id']) // Asegúrate de seleccionar la columna de orden
                    ->where('nt_id', $v->nt_id)
                    ->get();

                foreach ($capacidades as $cap) {
                    $capKey = $cap->nc_descripcion; // Usar el orden explícito

                    if (!isset($notasOrganizadas[$capKey])) {
                        $notasOrganizadas[$capKey] = [];
                    }

                    if ($v->nt_bimestre <= $numeroPeriodos) {
                        $notasOrganizadas[$capKey]["B" . $v->nt_bimestre] = [
                            "nota" => $cap->nc_nota ?? '--',
                            "idNotaPadre" => $cap->nt_id ?? 0
                        ];
                    }
                }
            }

            // Completar períodos faltantes con notas nulas
            foreach ($notasOrganizadas as $capKey => &$bimestres) {
                for ($i = 1; $i <= $numeroPeriodos; $i++) {
                    if (!isset($bimestres["B" . $i])) {
                        $bimestres["B" . $i] = ["nota" => NULL];
                        $bimestres["B" . $i]["idNota"] = -1;
                    }
                }

                // Calcular promedio solo con las notas existentes
                $notasValores = array_column($bimestres, 'nota');
                $notasFiltradas = array_filter($notasValores, function ($nota) {
                    return $nota !== '0' && $nota !== '--' && $nota !== NULL;
                });

                $promedio = !empty($notasFiltradas) ? $this->calcularPromedio($notasFiltradas) : '';
                $bimestres["Promedio"] = ["nota" => $promedio, "idNota" => -1];
            }


            $g->notas = $notasOrganizadas;
            $g->promedioValor = $this->buscarInfoNotas($g->promedio);
        }

        return $Gsas;
    }



    public function calcularPromedio($notas)
    {

        if (empty($notas)) return ""; // Si el array está vacío, devolver vacío

        // Jerarquía de notas
        $jerarquia = ["AD" => 5, "A" => 4, "B" => 3, "C" => 2, "D" => 1];

        // Contar frecuencia de cada nota
        $conteo = array_count_values($notas);

        // Encontrar la(s) nota(s) con mayor frecuencia
        $maxFrecuencia = max($conteo);
        $notasMasFrecuentes = array_keys($conteo, $maxFrecuencia);

        if (count($notasMasFrecuentes) === 1) {
            return $notasMasFrecuentes[0]; // Si hay una sola nota más frecuente, devolverla
        }

        // Si hay empate, elegir según la jerarquía
        usort($notasMasFrecuentes, function ($a, $b) use ($jerarquia) {
            return ($jerarquia[$b] ?? 0) <=> ($jerarquia[$a] ?? 0);
        });

        return $notasMasFrecuentes[0]; // Retornar la nota con mayor jerarquía


    }

    public function updateCapacidad(Request $request)
    {
        Log::info("----------------------------------------");
        Log::info($request->all());
        Log::info("----------------------------------------");
        try {
            $bimestre = $request->bimestre; //*
            $idAlumno = $request->idAlumno; //*
            $personalAcademico = $request->personalAcademico; //*
            $cursoId = $request->cursoId; //*
            $agsId = $request->agsId; //*
            $idNota = $request->idNota;

            $idCapacidad = $request->idCapacidad;
            $idPeriodo = $request->idPeriodo;
            $notaSeleccionada = $request->notaSeleccionada;
            if ($idNota != -1) {
                //$NotaPromoedio = Nota::where('nt_id', $idNota)->first();
                $notaCapacidad  = NotaCapacidad::where('nt_id', $idNota)->where('cap_id', $idCapacidad)->first();

                if ($notaCapacidad) {
                    $notaCapacidad->nc_nota = $notaSeleccionada;
                    $notaCapacidad->save();
                    //ahora quiero que me busques todas las notas de la capacidad y me devuelvas el promedio
                    $notasCapacidad = NotaCapacidad::where('nt_id', $idNota)->get();
                    $notasValores = array_column($notasCapacidad->toArray(), 'nc_nota');
                    $notasFiltradas = array_filter($notasValores, function ($nota) {
                        return $nota !== '0' && $nota !== '--' && $nota !== NULL;
                    });
                    $promedio = !empty($notasFiltradas) ? $this->calcularPromedio($notasFiltradas) : '';
                    $NotaPromoedio = Nota::where('nt_id', $idNota)->first();
                    $NotaPromoedio->nt_nota = $promedio;
                    $NotaPromoedio->save();
                    //si se registro correctamente la nota, se actualiza el promedio
                    $NotaPromoedio->nt_nota = $this->calcularPromedio($NotaPromoedio->nt_id);
                    $NotaPromoedio->estadoPromedio = 0;
                    $NotaPromoedio->save();
                } else {
                    $NotaCapacidadRegistro = NotaCapacidad::Create([
                        'nc_descripcion' => $idCapacidad,
                        'nc_nota' => $notaSeleccionada,
                        'nt_id' => $idNota,
                        'nc_is_deleted' => 0
                    ]);
                    $NotaPromoedio = Nota::where('nt_id', $idNota)->first();
                    $NotaPromoedio->estadoPromedio = 0;
                    $NotaPromoedio->save();
                }
            } else {

                $NotaPromoedio = Nota::where('alu_id', $idAlumno)->where('curso_id', $cursoId)->where('pa_id', $personalAcademico)->where('per_id', $idPeriodo)->where('nt_bimestre', substr($bimestre, 1))->first();
                if (!$NotaPromoedio) {

                    $NotaPromoedio = Nota::Create([
                        'per_id' => $idPeriodo,
                        'nt_bimestre' => substr($bimestre, 1),
                        'nt_nota' => 0,
                        'ags_id' => $agsId,
                        'curso_id' => $cursoId,
                        'alu_id' => $idAlumno,
                        'pa_id' => $personalAcademico
                    ]);

                    $NotaCapacidadRegistro = NotaCapacidad::Create([
                        'nc_descripcion' => $idCapacidad,
                        'nc_nota' => $notaSeleccionada,
                        'nt_id' => $NotaPromoedio->nt_id,
                        'nc_is_deleted' => 0
                    ]);

                    $NotaPromoedio->nt_nota = $notaSeleccionada;
                    $NotaPromoedio->estadoPromedio = 0;
                    $NotaPromoedio->save();
                } else {


                    $NotaCapacidadRegistro = NotaCapacidad::Create([
                        'nc_descripcion' => $idCapacidad,
                        'nc_nota' => $notaSeleccionada,
                        'nt_id' => $NotaPromoedio->nt_id,
                        'nc_is_deleted' => 0
                    ]);


                    $NotaPromoedio->estadoPromedio = 0;
                    $NotaPromoedio->save();
                }
            }

            return response()->json(['status' => 200, 'message' => 'Capacidad actualizada correctamente']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 404, 'message' =>  'Error al actualizar la capacidad: ' . $th->getMessage()]);
        }
    }


    //creame un metodo que va a actualizar todas las notas con el promedio de las capacidades

    public function updateNota()
    {
        $notas = Nota::where('estadoPromedio', 0)->select('nt_id', 'nt_nota', 'estadoPromedio')->get();

        foreach ($notas as $nota) {
            $promedio = Nota::where('nt_id', $nota->nt_id)->first();
            $capacidadNota = NotaCapacidad::where('nt_id', $nota->nt_id)->pluck('nc_nota');
            $nuevoPromedio = $this->calcularPromedio($capacidadNota->toArray());

            $promedio->nt_nota = $nuevoPromedio;
            $promedio->estadoPromedio = 1;
            $promedio->save();
        }
        return  $notas;
    }

    //año ,grado ,nivel ,seccion




}
