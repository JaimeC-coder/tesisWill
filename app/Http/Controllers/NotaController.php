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





    public function returnCapacitaciones($capacitacion)
    {

        $capacidades = [];

        for ($i = 0; $i < count($capacitacion); $i++) {
            $capacidades["C" . $i + 1] = $capacitacion[$i]['cap_descripcion'];
        }

        return $capacidades;
    }



    public function inicio(Request $request, $nivels = null)
    {


        $user = Auth::user();
        $user = $user->per_id;

        $anios = Anio::where('anio_estado', '!=', 0)
            ->where('is_deleted', '!=', 1)
            ->whereIn('anio_id', function ($query) {
                $query->select('anio_id')
                    ->from('periodos')
                    ->where('per_estado', 1)
                    ->where('is_deleted', '!=', 1);
            })
            ->get();

        //de esta lista de años quiero que me devuelvas

        $nivel = $request->nivel;
        $anio = $request->anio;
        $docente = $request->docente;
        $grado = $request->grado;
        $seccion = $request->seccion;
        $cursoId = $request->cursoId;


        $curso = Curso::where('gra_id', $grado)
            ->where('niv_id', $nivel)
            ->where('cur_id', $cursoId)
            ->where('is_deleted', '!=', 1)
            ->first();

        if ($curso != null) {
            $asignacionesCursos = AsignarCurso::where('pa_id', $docente)
                ->where('curso', $curso->cur_nombre)
                ->where('asig_is_deleted', '!=', 1)
                ->first();

            $capacidades = Capacidad::where('cur_id', $curso->cur_id)
                ->where('cap_is_deleted', '!=', 1)
                ->get();

            $estado = 1;
            $anio = Anio::where('anio_id', $anio)->where('is_deleted', '!=', 1)->first();


            $periodo = Periodo::where('anio_id', $anio->anio_id)->where('per_estado', $estado)->where('is_deleted', 0)->first();


            $tipoPeriodo = Tipo::where('tp_id', $periodo->per_tp_notas)->first();


            $asignacionesCursos->capacidades = count($capacidades);

            $tipoPeriodo = $this->configurarTipoPeriodo($tipoPeriodo);
            $Gsas = $this->getGsas($nivel, $grado, $seccion, $curso, $docente, $tipoPeriodo, $capacidades, $periodo->per_id);

        
            return view('view.notas.inicio', compact('anios', 'asignacionesCursos', 'Gsas', 'tipoPeriodo', 'capacidades', 'user'));
        }

        $asignacionesCursos = null;
        $Gsas = null;
        $tipoPeriodo = null;



        return view('view.notas.inicio', compact('anios', 'asignacionesCursos', 'Gsas', 'tipoPeriodo', 'user'));
    }

    /**
     * Configura el tipo de periodo según su tipo
     */
    private function configurarTipoPeriodo($tipoPeriodo)
    {
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

        return $tipoPeriodo;
    }

    public function getGsas($nivel, $grado, $seccion, $curso, $docente, $tipoPeriodo, $capacidades, $per_id)
    {
        $Gsas1 = Gsa::select('ags_id')
            ->where('niv_id', $nivel)
            ->where('gra_id', $grado)
            ->where('sec_id', $seccion)
            ->where('is_deleted', '!=', 1)
            ->get();

        $numeroPeriodos = $tipoPeriodo->cantidad;


        $capacidadesPlantilla = [];
        foreach ($capacidades as $capacidad) {
            $capacidadesPlantilla[$capacidad->cap_id] = [
                'descripcion' => $capacidad->cap_descripcion,
                'orden' => $capacidad->cap_id, // Asumiendo que hay un campo de orden o usamos cap_id
            ];
        }
        $gsaProcesados = [];

        foreach ($Gsas1 as $g) {
            $matricula = Matricula::where('ags_id', $g->ags_id)
                ->where('is_deleted', '!=', 1)
                ->where('per_id', $per_id)
                ->first();


          if (!$matricula) {
            continue;
        }


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



            // Obtener todas las notas del alumno para este curso y docente
            $notas = Nota::select(['nt_bimestre', 'nt_nota', 'nt_id'])
                ->where('alu_id', $alumno->alu_id)
                ->where('curso_id', $curso->cur_id)
                ->where('pa_id', $docente)
                ->where('nt_is_deleted', 0)
                ->get();


            // Inicializar la estructura de notas por capacidad
            $notasOrganizadas = [];
            foreach ($capacidadesPlantilla as $capId => $capInfo) {
                $notasOrganizadas[$capId] = [
                    'descripcion' => $capInfo['descripcion'],

                    'orden' => $capInfo['orden'],
                    'periodos' => []
                ];

                // Inicializar periodos con valores vacíos
                for ($i = 1; $i <= $numeroPeriodos; $i++) {
                    $notasOrganizadas[$capId]['periodos']["P" . $i] = [
                        "nota" => NULL,
                        "idNotaPadre" => 0
                    ];
                }
            }


            // Rellenar con las notas existentes
            foreach ($notas as $nota) {
                // Buscar las capacidades asociadas a esta nota
                $notasCapacidades = NotaCapacidad::where('nt_id', $nota->nt_id)
                    ->get();
                Log::info($notasCapacidades);

                foreach ($notasCapacidades as $notaCapacidad) {
                    $capId = $notaCapacidad->cap_id;

                    // Verificar si esta capacidad existe en nuestra plantilla
                    if (isset($notasOrganizadas[$capId])) {
                        $periodoKey = "P" . $nota->nt_bimestre;

                        // Solo actualizar si el período es válido
                        if ($nota->nt_bimestre <= $numeroPeriodos) {
                            $notasOrganizadas[$capId]['periodos'][$periodoKey] = [
                                "nota" => $notaCapacidad->nc_nota ?? '--',
                                "idNotaPadre" => $nota->nt_id
                            ];
                        }
                    }
                }
            }

            // Calcular promedios por capacidad
            foreach ($notasOrganizadas as $capId => &$capacidadData) {
                $notasValues = array_column($capacidadData['periodos'], 'nota');
                $notasFiltradas = array_filter($notasValues, function ($nota) {
                    return $nota !== '0' && $nota !== '--' && $nota !== NULL;
                });

                $promedio = !empty($notasFiltradas) ? $this->calcularPromedio($notasFiltradas) : '';
                $capacidadData['promedio'] = $promedio;
            }

            $g->notas = $notasOrganizadas;

            // Calcular promedio general (opcional)
            $promediosCapacidades = array_column($notasOrganizadas, 'promedio');
            $promediosFiltrados = array_filter($promediosCapacidades, function ($prom) {
                return $prom !== '' && $prom !== NULL;
            });

            $g->promedioGeneral = !empty($promediosFiltrados) ? $this->calcularPromedio($promediosFiltrados) : '';
            $gsaProcesados[] = $g;
        }

        return $gsaProcesados;
    }

    private function limpiarGsa(&$gsa)
    {
        $gsa->periodoID = null;
        $gsa->alumno = null;
        $gsa->dni = null;
        $gsa->idAlumno = null;
        $gsa->notas = [];
        $gsa->promedioGeneral = '';
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
            if ($idNota != 0) {
                Log::info("entro 1: ");
                //$NotaPromoedio = Nota::where('nt_id', $idNota)->first();
                $notaCapacidad  = NotaCapacidad::where('nt_id', $idNota)->where('cap_id', $idCapacidad)->first();
                Log::info($notaCapacidad);
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
                    Log::info("entro 2: ");
                    $NotaCapacidadRegistro = NotaCapacidad::Create([
                        'nc_descripcion' => 'C1',
                        'cap_id' => $idCapacidad,
                        'nc_nota' => $notaSeleccionada,
                        'nt_id' => $idNota,
                        'nc_is_deleted' => 0
                    ]);
                    $NotaPromoedio = Nota::where('nt_id', $idNota)->first();
                    $NotaPromoedio->estadoPromedio = 0;
                    $NotaPromoedio->save();
                }
            } else {
                Log::info("entro 3: ");

                $NotaPromoedio = Nota::where('alu_id', $idAlumno)->where('curso_id', $cursoId)->where('pa_id', $personalAcademico)->where('per_id', $idPeriodo)->where('nt_bimestre', $bimestre)->first();
                if (!$NotaPromoedio) {
                    Log::info("entro 5: ");
                    $NotaPromoedio = Nota::Create([
                        'per_id' => $idPeriodo,
                        'nt_bimestre' => $bimestre,
                        'nt_nota' => 0,
                        'ags_id' => $agsId,
                        'curso_id' => $cursoId,
                        'alu_id' => $idAlumno,
                        'pa_id' => $personalAcademico
                    ]);

                    $NotaCapacidadRegistro = NotaCapacidad::Create([
                        'nc_descripcion' => $idCapacidad,
                        'cap_id' => $idCapacidad,
                        'nc_nota' => $notaSeleccionada,
                        'nt_id' => $NotaPromoedio->nt_id,
                        'nc_is_deleted' => 0
                    ]);

                    $NotaPromoedio->nt_nota = $notaSeleccionada;
                    $NotaPromoedio->estadoPromedio = 0;
                    $NotaPromoedio->save();
                } else {
                    Log::info("entro4: ");

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
}
