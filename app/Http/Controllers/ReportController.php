<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Anio;
use App\Models\AsignarCurso;
use App\Models\Aula;
use App\Models\Capacidad;
use App\Models\Curso;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Grado;
use App\Models\Gsa;
use App\Models\Institucion;
use App\Models\Matricula;
use App\Models\Nivel;
use App\Models\Nota;
use App\Models\NotaCapacidad;
use App\Models\Apoderado;
use App\Models\Persona;
use App\Models\PersonalAcademico;
use App\Models\Provincia;
use App\Models\Rol;
use App\Models\Seccion;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function inicio()
    {

        $anios = Anio::where('anio_estado', '!=', 0)->get();
        $niveles = Nivel::all();
        return view('view.reporte.general', compact('anios', 'niveles'));
    }

    public function matricula(Request $request)
    {

        $anio_id = $request->anio_id;
        $nivel_id = $request->nivel_id;

        $grados = [];
        $totales = [];
        $data = Grado::where('niv_id', $nivel_id)->get();
        foreach ($data as $d) {
            array_push($grados, $d->gra_descripcion);
            $data2 = Gsa::where('niv_id', $nivel_id)->where('is_deleted', '!=', 1)->where('gra_id', $d->gra_id)->get();
            array_push($totales, count($data2));
        }

        return response()->json([
            'grados' => $grados,
            'totales' => $totales
        ]);
    }
    public function personal(Request $request)
    {
        $anio_id = $request->anio_id;
        $nivel_id = $request->nivel_id;

        $turno = $nivel_id == 1 ? 'Mañana' : 'Tarde';

        $cargos = [];
        $totales = [];
        $data = Rol::where('is_deleted', '!=', 1)->whereNotIn('rol_id', [1, 3])->get();
        $total = count($data);
        foreach ($data as $d) {
            array_push($cargos, $d->rol_descripcion);
            $data2 = PersonalAcademico::where('rol_id', $d->rol_id)->get();
            array_push($totales, count($data2));
        }

        return response()->json([
            'cargos' => $cargos,
            'totales' => $totales,
            'total' => $total,
        ]);
    }
    public function sexo(Request $request)
    { {
            $anio_id = $request->anio_id;
            $nivel_id = $request->nivel_id;

            $sexos = ['Masculino', 'Femenino'];
            $totales = [];
            $f = 0;
            $m = 0;
            $dataOrm = gsa::where('niv_id', $nivel_id)->where('is_deleted', '!=', 1)->get();
            foreach ($dataOrm as $d) {
                $data2 = Matricula::where('ags_id', $d->ags_id)->first();
                if ($data2 !== null) {
                    $data3 = Alumno::where('alu_id', $data2->alu_id)->first();
                    $data4 = Persona::where('per_id', $data3->per_id)->first();
                    if ($data4->per_sexo == 'M') {
                        $m += 1;
                    } else {
                        $f += 1;
                    }
                }
            }
            $cantidades = [$m, $f];

            return response()->json([
                'sexos' => $sexos,
                'totales' => $cantidades
            ]);
        }
    }
    public function countPersonal(Request $request)
    {
        $anio_id = $request->anio_id;
        $nivel_id = $request->nivel_id;

        $turno = $nivel_id == 1 ? 'Mañana' : 'Tarde';

        $cursos = [];
        $totales = [];
        $data = Curso::selectRaw('cur_nombre, cur_abreviatura')->where('cur_horas', '>', 0)->where('niv_id', $nivel_id)->where('is_deleted', '!=', 1)->groupBy('cur_nombre', 'cur_abreviatura')->get();
        $total = count($data);
        foreach ($data as $d) {
            array_push($cursos, $d->cur_nombre);
            $data2 = AsignarCurso::where('curso', $d->cur_nombre)->where('asig_is_deleted', '!=', 1)->get();
            array_push($totales, count($data2));
        }

        return response()->json([
            'cursos' => $cursos,
            'totales' => $totales,
            'total' => $total,
        ]);
    }
    public function vacante(Request $request)
    {
        $anio_id = $request->anio_id;
        $nivel_id = $request->nivel_id;

        $seccion = [];
        $totales = [];
        $data = Grado::where('niv_id', $nivel_id)
            ->where('gra_is_delete', 0)
            ->where('gra_estado', 1)
            ->get();
        $total = count($data);
        foreach ($data as $d) {
            array_push($seccion, $d->gra_descripcion);
            $data2 = Seccion::where('gra_id', $d->gra_id)->where('sec_is_delete', '!=', 1)->sum('sec_vacantes');
            array_push($totales, $data2);
        }

        return response()->json([
            'seccion' => $seccion,
            'totales' => $totales,
            'total' => $total,
        ]);
    }

    public function gestion(Request $request)
    {
        // return $request;

        $anios = Anio::where('anio_estado', '!=', 0)->get();

        $anio = $request->anio;
        $nivel = $request->nivel;
        $grado = $request->grado;
        $seccion = $request->seccion;
        if ($anio == null || $nivel == null || $grado == null || $seccion == null) {
            $resultados = [];
        } else {
            $result = $this->getCoursesWithAvgsAndNotes($anio, $nivel, $grado, $seccion);
            // Agrupar por curso_id
            $resultados = $this->dataREST($result);
        }
        return view('view.reporte.gestion', compact('resultados', 'anios'));
    }

    public function dataREST($result)
    {
        $agrupadoPorCurso = $result->groupBy('curso_id');

        $resultados = [];

        foreach ($agrupadoPorCurso as $cursoId => $notasCurso) {
            $totalNotas = $notasCurso->count();

            $countA_AD = $notasCurso->filter(function ($nota) {
                return $nota->nt_nota === 'A' || $nota->nt_nota === 'AD';
            })->count();

            $porcentaje = $totalNotas > 0 ? ($countA_AD / $totalNotas) * 100 : 0;

            $countB = $notasCurso->filter(function ($nota) {
                return $nota->nt_nota === 'B';
            })->count();

            $porcentajeB = $totalNotas > 0 ? ($countB / $totalNotas) * 100 : 0;

            $countC = $notasCurso->filter(function ($nota) {
                return $nota->nt_nota === 'C';
            })->count();

            $porcentajeC = $totalNotas > 0 ? ($countC / $totalNotas) * 100 : 0;

            // Obtener el nombre del curso usando la relación
            $nombreCurso = optional($notasCurso->first()->curso)->cur_nombre;
            $total = round($porcentaje, 2) + round($porcentajeB, 2) + round($porcentajeC, 2);

            $resultados[] = [
                'curso_id' => $cursoId,
                'curso_nombre' => $nombreCurso,
                'total_notas' => $totalNotas,
                'aprobados_A_AD' => $countA_AD,
                'aprobados_B' => $countB,
                'aprobados_C' => $countC,
                'porcentaje_aprobados' => round($porcentaje, 2),
                'porcentaje_B' => round($porcentajeB, 2),
                'porcentaje_C' => round($porcentajeC, 2),
                'Total' => round($total, 1)
            ];
        }
        return $resultados;
    }


    public function getReportCoursePDF(Request $request)
    {
        $anio = $request->anio;
        $nivel = $request->nivel;
        $grado = $request->grado;
        $seccion = $request->seccion;

        $result = $this->getCoursesWithAvgsAndNotes($anio, $nivel, $grado, $seccion);
        // Agrupar por curso_id
        $cursosList = $this->dataREST($result);


        $nivel = Nivel::where('niv_id', $nivel)->select('niv_descripcion')->first();
        $grado = Grado::where('gra_id', $grado)->select('gra_descripcion')->first();
        $seccion = Seccion::where('sec_id', $seccion)->select('sec_descripcion')->first();
        $perido = Anio::where('anio_id', $anio)->select('anio_descripcion')->first();
        $infoColegio = Institucion::where('ie_id', 1)->first();


        $headers = [
            'nivel' => $nivel->niv_descripcion,
            'grado' => $grado->gra_descripcion,
            'seccion' => $seccion->sec_descripcion,
            'anio' => $perido->anio_descripcion,
            'infoColegio' => $infoColegio,
        ];
        $pdf = Pdf::loadView('view.reporte.promediosCursos', compact('cursosList', 'headers'));
        return $pdf->stream();
    }

    public function alumno(Request $request)
    {
        $user = Auth::user();

        if (!$user || empty($user->roles)) {
            Log::warning('Usuario no autenticado o sin roles');
            return redirect()->back()->with('error', 'Usuario no válido.');
        }

        $persona = Persona::where('per_id', $user->per_id)->first();

        // Determinar si el usuario es un alumno
        $isAlumno = $user->roles[0]->name === "Alumno";
        $isApoderado = $user->roles[0]->name === "Apoderado";
        $dni = $isAlumno ? $persona->per_dni : $request->buscar;

        // Variables para la vista
        $alumno = $gsa = $aula = $matricula = $nivel = $grado = $seccion = null;

        if ($isApoderado &&  $request->buscar) {
            //primero buscamos en ta tabla de apoderado el id
            $apoderado = Apoderado::where('per_id', $persona->per_id)->first();
            //luego en la tabla de alumnos cuales tiene el id de ese apodero
           // return $apoderado;
            $alumnos = Alumno::where('apo_id', $apoderado->apo_id)
                ->with(['persona'])
            ->get();
            //return $alumnos;
            // luego buscamos el dni de esos alumnos
            $dnis = $alumnos->pluck('persona.per_dni')->toArray();
           // return $dnis;

            //luego comparamos si el dni ingresa pertene a alguno de los dni de sus hijo si es asi que continue
            if (!in_array( $request->buscar, $dnis)) {
                Log::info("DNI no pertenece a los hijos del apoderado: $dni");
                return view('view.reporte.alumno', compact('dni', 'persona', 'alumno', 'gsa', 'matricula', 'nivel', 'grado', 'seccion'))
                    ->with('error', "El DNI proporcionado no pertenece a ningún hijo del apoderado.");
            }
            // si no que que mande un error

        }
        // Validar si se recibió un DNI (caso: no es alumno y no envió nada)
        if (is_null($dni)) {
            Log::info('DNI no proporcionado');
            return view('view.reporte.alumno', compact('dni', 'persona', 'alumno', 'gsa', 'matricula', 'nivel', 'grado', 'seccion'))
                ->with('errors', 'Debe proporcionar un DNI.');
        }

        // Buscar al alumno por DNI
        $alumno = Alumno::whereHas('persona', function ($query) use ($dni) {
            $query->where('per_dni', $dni);
        })->with('persona')->first();

        if (!$alumno) {
            Log::info("No se encontró alumno con DNI: $dni");
            return view('view.reporte.alumno', compact('dni', 'persona', 'alumno', 'gsa', 'matricula', 'nivel', 'grado', 'seccion'))
                ->with('error', "No se encontró un alumno con el DNI: $dni.");
        }

        // Buscar matrícula
        $matricula = Matricula::where('alu_id', $alumno->alu_id)->first();

        if (!$matricula) {
            Log::info("No se encontró matrícula para el DNI: $dni");
            return view('view.reporte.alumno', compact('dni', 'persona', 'alumno', 'gsa', 'matricula', 'nivel', 'grado', 'seccion'))
                ->with('error', "No se encontró matrícula para el DNI: $dni.");
        }

        // Consultar datos asociados
        $gsa = Gsa::find($matricula->ags_id);
        $aula = Aula::find($gsa->ala_id ?? null);
        $nivel = Nivel::find($gsa->niv_id ?? null);
        $grado = Grado::find($gsa->gra_id ?? null);
        $seccion = Seccion::find($gsa->sec_id ?? null);

        return view('view.reporte.alumno', compact('dni', 'persona', 'alumno', 'gsa', 'matricula', 'nivel', 'grado', 'seccion'));
    }


    public function getCoursesWithAvgsAndNotes($year, $level, $grade, $section)
    {
        // Obtener todos los GSA que coincidan
        $GSANotas = Gsa::where('niv_id', $level)
            ->where('gra_id', $grade)
            ->where('sec_id', $section)
            ->where('is_deleted', '!=', 1)
            ->pluck('ags_id');

        // Obtener las notas con el curso cargado (eager loading)
        $notas = Nota::with('curso') // Carga la relación para obtener el nombre del curso
            ->whereIn('ags_id', $GSANotas)
            ->where('per_id', $year)
            ->where('nt_is_deleted', '!=', 1)
            ->get();


        return $notas;
    }

    public function generarFichaMatricula(Request $request)
    {
        $data = $request->per_id;

        $institucion = Institucion::where('ie_id', 1)->first();


        $año = Anio::where('anio_estado', '!=', 0)->first();

        $Persona = Persona::where('per_id', $data)->first();
        $departamento = Departamento::where('idDepa', $Persona->per_departamento)->first();
        $provincia = Provincia::where('idProv', $Persona->per_provincia)->first();
        $distrito = Distrito::where('idDist', $Persona->per_distrito)->first();

        $alumno = $Persona["per_nombres"] . " " . $Persona["per_apellidos"];

        if (isset($Persona["per_nombre_completo"]) == false) {
            $alumno = $Persona["per_nombres"] . " " . $Persona["per_apellidos"];
        } else {
            $alumno = $Persona["per_nombre_completo"];
        }
        // $idNivel = $data["idNivel"] ?? $Persona->alumno->gsa->matricula->nivel->niv_id;
        // $nivel ="hola1";
        $nivel = $Persona->alumno->ultimaMatricula?->gsa?->nivel?->niv_descripcion; //TODO

        // $idGrado = $data["idGrado"];

        $grado = $Persona->alumno->ultimaMatricula?->gsa?->grado?->gra_descripcion; //TODO
        // $idSeccion = $data["idSeccion"];
        $seccion = $Persona->alumno->ultimaMatricula?->gsa?->seccion?->sec_descripcion; //TODO

        $apoderado = $Persona->alumno->apoderado->persona->per_apellidos . "," . $Persona->alumno->apoderado->persona->per_nombres;


        $apoderadoNombre = $Persona->alumno->apoderado->persona->per_nombres;
        $apoderadoApellidos = explode(" ", $Persona->alumno->apoderado->persona->per_apellidos);

        $parentesco = strtoupper($Persona->alumno->apoderado->apo_parentesco);
        $vive = $Persona->alumno->apoderado->apo_vive_con_estudiante == 'No' ? 2 : 1;
        $fechaNacimientoApo = explode("-", $Persona->alumno->apoderado->persona->per_fecha_nacimiento);
        // $parentesco = $Persona->alumno->apoderado->apo_id;
        //dd($Persona->alumno->apoderado->apo_vive_con_estudiante);

        $dni = $Persona->alumno->persona->per_dni;
        $email = $Persona["per_email"];
        $estadoCivil = $Persona["per_estado_civil"];

        $fechaNacimiento = explode("-", $Persona->alumno->persona->per_fecha_nacimiento);

        $sexo = $Persona["per_sexo"] == 'Masculino' ? 'M' : 'F';

        $direccion = $Persona["per_direccion"];

        $celular = $Persona["per_celular"];
        $Apodireccion = $Persona->alumno->apoderado->persona->per_direccion;
        $Apocelular = $Persona->alumno->apoderado->persona->per_celular;
        $pais = $Persona["per_pais"];
        $departamento = ($Persona->per_departamento == 0 ? 'LA LIBERTAD' : $departamento->departamento);
        $provincia = ($Persona->per_provincia == 0 ? 'CHEPÉN' : $provincia->provincia);
        $distrito = ($Persona->per_distrito == 0 ? 'CHEPÉN' : $distrito->distrito);

        // Obteniendo imagen y convintiendolo a base64
        $educacion = public_path('escudoMinedu.png');
        $contenidoBinario1 = file_get_contents($educacion);
        $imagenEducacion = base64_encode($contenidoBinario1);

        $nombreDocumento = "FICHA_MATRICULA-" . str_replace(' ', '_', $alumno)  . ".pdf";

        // Creando Pdf
        include_once "../vendor/autoload.php";
        $dompdf = new Dompdf();
        $dompdf->setPaper('A3', 'landscape');

        $dompdf->loadHtml('
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>' . $nombreDocumento . '</title>
            </head>
            <style>
                body {
                    font-family: sans-serif;
                    font-size: 0.75rem;
                }
                .logo {
                    width: 6rem;
                    height: 6rem;
                    object-fit: fill;
                }
                .title-minedu {
                    font-family: Times New Roman, Times, serif;
                    margin: 0;
                }
                .table {
                    border-collapse: collapse;
                }
                .table tr th,
                .table tr td {
                    padding: .25rem .125rem;
                    border: 1px solid black;
                }
                .box-1 {
                    height: 0.7rem;
                    width: 1.75rem;
                }
                .p-1 {
                    padding: .25rem;
                }
                .bg-gray {
                    background-color: #d3d3d3;
                    font-weight: 600;
                }
                .rotation {
                    writing-mode: vertical-rl;
                    transform: rotate(180deg);
                    text-align: center;
                }
            </style>
            <body>
                <table style="margin-bottom: 2.5rem;">
                    <tbody>
                        <tr>
                            <td width="20%" align="center">
                                <img src="data:image/jpg;base64,' . $imagenEducacion . '" alt="escudo" class="logo" />
                                <h4 class="title-minedu">Ministerio de Educación</h4>
                            </td>
                            <td width="50%">
                                <h1 align="center">FICHA ÚNICA DE MATRÍCULA</h1>
                            </td>
                            <td width="30%">
                                <table width="100%" class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="14" class="bg-gray">Tipo de Documento de Identidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="2" align="left" class="bg-gray">D.N.I</th>
                                            <td colspan="2" align="center">X</td>
                                            <th class="bg-gray">C.E</th>
                                            <td colspan="2"></td>
                                            <th colspan="2" class="bg-gray">Otro</th>
                                            <td></td>
                                            <th class="bg-gray" colspan="4">Especificar</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="bg-gray" align="left">N°</th>
                                            <td align="center" class="box-1">' . $dni[0] . '</td>
                                            <td align="center" class="box-1">' . $dni[1] . '</td>
                                            <td align="center" class="box-1">' . $dni[2] . '</td>
                                            <td align="center" class="box-1">' . $dni[3] . '</td>
                                            <td align="center" class="box-1">' . $dni[4] . '</td>
                                            <td align="center" class="box-1">' . $dni[5] . '</td>
                                            <td align="center" class="box-1">' . $dni[6] . '</td>
                                            <td align="center" class="box-1">' . $dni[7] . '</td>
                                            <td colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray" align="center" colspan="10">Código del Estudiante</th>
                                            <td class="bg-gray" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="p-1">
                                                <div style="height: 70px; text-align: center;">
                                                    Año de ingreso
                                                </div>
                                            </td>
                                            <td class="p-1" colspan="7">
                                                <div style="height: 70px; text-align: center;">
                                                    Código matricular de la Institución Educativa donde ingresó
                                                </div>
                                            </td>
                                            <td class="p-1" colspan="4">
                                                <div style="height: 70px; text-align: center;">
                                                    N° de Matricula generado por la Institución Educativa
                                                </div>
                                            </td>
                                            <td class="p-1">
                                                <div style="height: 70px; text-align: center;">
                                                    Flag
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="box-1"></td>
                                            <td class="box-1"></td>
                                            <td class="box-1"></td>
                                            <td class="box-1"></td>
                                            <td class="box-1"></td>
                                            <td class="box-1"></td>
                                            <td class="box-1"></td>
                                            <td class="box-1"></td>
                                            <td class="box-1"></td>
                                            <td class="box-1"></td>
                                            <td class="box-1"></td>
                                            <td class="box-1"></td>
                                            <td class="box-1"></td>
                                            <td class="box-1"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="14" style="border: none; font-size: 11px;">
                                                (Registrar sólo N° de D.N.I. El código del estudiante se anotará únicamente en caso
                                                de que el estudiante no tenga DNI. Este número será el único que utilizará durante
                                                su permanecia en el Sistema Educativo)
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <b>1. Datos Generales del Estudiante</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <b>1.1 Datos Personales</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table width="100%" class="table" style="text-align: center;">
                                    <tbody>
                                        <tr>
                                            <th class="bg-gray">Apellidos y Nombres</th>
                                            <th class="bg-gray" colspan="4">Sexo</th>
                                            <th class="bg-gray">Estado Civil(1)</th>
                                            <th class="bg-gray" colspan="4">Nacimiento Registrado(2)</th>
                                        </tr>
                                        <tr>
                                            <td align="center">' . $alumno . '</td>
                                            <td align="center" class="box-1">H</td>
                                            <td align="center" class="box-1">' . ($sexo == 'M' ? 'X' : '') . '</td>
                                            <td align="center" class="box-1">M</td>
                                            <td align="center" class="box-1">' . ($sexo == 'F' ? 'X' : '') . '</td>
                                            <td>SOLTERO</td>
                                            <td align="center" class="box-1">Sí</td>
                                            <td align="center" class="box-1">X</td>
                                            <td align="center" class="box-1">No</td>
                                            <td align="center" class="box-1"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" style="margin-bottom: 2.5rem;">
                    <tbody>
                        <tr>
                            <td width="50%"></td>
                            <th width="25%">1.1.1 Desarrollo del Estudiante</th>
                            <td width="25%">(Obligatorio para nivel inicial)</td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" class="table" style="text-align: center;">
                                    <tbody>
                                        <tr>
                                            <td rowspan="2" class="bg-gray">Fecha de Nacimiento</td>
                                            <td class="bg-gray">Día</td>
                                            <td class="bg-gray">Mes</td>
                                            <td class="bg-gray">Año</td>
                                            <td rowspan="2" class="bg-gray">Lengua Materna</td>
                                            <td rowspan="2" colspan="12">CASTELLANO</td>
                                        </tr>
                                        <tr>
                                            <td align="center">' . $fechaNacimiento[2] . '</td>
                                            <td align="center">' . $fechaNacimiento[1] . '</td>
                                            <td align="center">' . $fechaNacimiento[0] . '</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray">Lugar de nacimiento</td>
                                            <td colspan="3">' . $distrito . '</td>
                                            <td class="bg-gray">Segunda lengua</td>
                                            <td colspan="12">NINGUNO</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray">País</td>
                                            <td colspan="3">' . $pais . '</td>
                                            <td class="bg-gray">Religión</td>
                                            <td colspan="12"></td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray">Departamento</td>
                                            <td colspan="3">' . $departamento . '</td>
                                            <td class="bg-gray">Número de hermanos</td>
                                            <td colspan="3"></td>
                                            <td class="bg-gray" colspan="6">Lugar que ocupa</td>
                                            <td colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray">Provincia</td>
                                            <td colspan="3">' . $provincia . '</td>
                                            <td class="bg-gray">Tipo de Discapacidad(3)</td>
                                            <td class="box-1 bg-gray" align="center">DI</td>
                                            <td class="box-1" align="center"></td>
                                            <td class="box-1 bg-gray" align="center">DA</td>
                                            <td class="box-1" align="center"></td>
                                            <td class="box-1 bg-gray" align="center">DV</td>
                                            <td class="box-1" align="center"></td>
                                            <td class="box-1 bg-gray" align="center">DM</td>
                                            <td class="box-1" align="center"></td>
                                            <td class="box-1 bg-gray" align="center">SC</td>
                                            <td class="box-1" align="center"></td>
                                            <td class="box-1 bg-gray" align="center">OT</td>
                                            <td class="box-1" align="center"></td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray">Distrito</td>
                                            <td colspan="3">' . $distrito . '</td>
                                            <td class="bg-gray">Certif. de discapacidad *</td>
                                            <td class="bg-gray" colspan="4">Tiene:</td>
                                            <td colspan="2" align="center"></td>
                                            <td class="bg-gray" colspan="4">No tiene:</td>
                                            <td colspan="2" align="center">X</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 2.125rem;" colspan="17"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table width="100%" class="table" style="text-align: center;">
                                    <tbody>
                                        <tr>
                                            <td class="bg-gray" colspan="4" align="center">Nacimiento</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray" align="center">Normal</td>
                                            <td class="box-1"></td>
                                            <td class="bg-gray" align="center">Cesárea</td>
                                            <td class="box-1"></td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray" colspan="3" align="center">Con complicaciones</td>
                                            <td class="box-1"></td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray" colspan="4" align="center">Observaciones</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="height: 6.125rem;">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table width="100%" class="table" style="text-align: center;">
                                    <tbody>
                                        <tr>
                                            <td class="bg-gray" align="center">Apecto</td>
                                            <td class="bg-gray" align="center">Actividad</td>
                                            <td class="bg-gray" align="center">Edad</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray" rowspan="6" align="center">Psicomotriz</td>
                                            <td class="bg-gray">Levantó la cabeza</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray">Se sentó</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray">Gateó</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray">Se paró</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray">Caminó</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray">Controló sus esfínteres</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray" rowspan="2" align="center">Lenguaje</td>
                                            <td class="bg-gray">Habló las primeras palabras</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray">Habló con fluidez</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" style="margin-bottom: 5.5rem;">
                    <tbody>
                        <tr>
                            <td width="55%"><b>1.1.2 Controles de Salud del Estudiante</b></td>
                            <td width="45%"><b>1.1.3 Estado de salud del Estudiante</b></td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" class="table" style="text-align: center;">
                                    <tbody>
                                        <tr>
                                            <td colspan="8" class="bg-gray">Control de Peso - Talla</td>
                                            <td colspan="8" class="bg-gray">Otros Controles</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="bg-gray">Fecha</td>
                                            <td rowspan="2" class="bg-gray">Peso</td>
                                            <td rowspan="2" class="bg-gray">Talla</td>
                                            <td rowspan="2" colspan="3" class="bg-gray">Observaciones</td>

                                            <td colspan="3" class="bg-gray">Fecha</td>
                                            <td rowspan="2" class="bg-gray">Tipo de Control</td>
                                            <td rowspan="2" colspan="4" class="bg-gray">Resultado</td>
                                        </tr>
                                        <tr>
                                            <td class="bg-gray">Día</td>
                                            <td class="bg-gray">Mes</td>
                                            <td class="bg-gray">Año</td>
                                            <td class="bg-gray">Día</td>
                                            <td class="bg-gray">Mes</td>
                                            <td class="bg-gray">Año</td>
                                        </tr>
                                        <tr>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;" colspan="3"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;" colspan="3"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;" colspan="3"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;" colspan="3"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;" colspan="3"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;" colspan="3"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;" colspan="3"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 1.125rem;" colspan="16"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table width="100%" class="table" style="text-align: center;">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Enfermedades sufridas</td>
                                            <td colspan="2" class="bg-gray">Vacunas</td>
                                            <td colspan="4" class="bg-gray">Alergias</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="bg-gray">Edad aprox.</td>
                                            <td colspan="1" class="bg-gray">Enfermedad</td>
                                            <td colspan="1" class="bg-gray">Edad aprox.</td>
                                            <td colspan="1" class="bg-gray">Enfermedad</td>
                                            <td rowspan="3" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td colspan="4" class="bg-gray">Eperiencias Traumáticas</td>
                                        </tr>
                                        <tr>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td rowspan="3" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td style="height: 0.7rem;"></td>
                                            <td rowspan="2" colspan="2" class="bg-gray">Tipo de Sangre</td>
                                            <td rowspan="2" colspan="2" style="width: 5rem;"></td>
                                        </tr>
                                        <tr>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                            <td  style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 1.125rem;" colspan="8"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" style="margin-bottom: 2.5rem;">
                    <tbody>
                        <tr>
                            <td width="60%"><b>1.1.4 Datos del domicilio del Estudiante</b></td>
                            <td width="40%"><b>1.1.4 Datos de los padres</b></td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" class="table" style="text-align: center;">
                                    <tbody>
                                        <tr>
                                            <td colspan="1" class="bg-gray">Año</td>
                                            <td colspan="3" class="bg-gray">Direcciòn</td>
                                            <td colspan="2" class="bg-gray">Lugar</td>
                                            <td colspan="3" class="bg-gray">Departamento</td>
                                            <td colspan="2" class="bg-gray">Provincia</td>
                                            <td colspan="2" class="bg-gray">Distrito</td>
                                            <td colspan="2" class="bg-gray">Teléfono</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;">' . $año->año_descripcion . '</td>
                                            <td colspan="3" style="height: 0.7rem;">' . $direccion . '</td>
                                            <td colspan="2" style="height: 0.7rem;">' . $distrito . '</td>
                                            <td colspan="3" style="height: 0.7rem;">' . $departamento . '</td>
                                            <td colspan="2" style="height: 0.7rem;">' . $provincia . '</td>
                                            <td colspan="2" style="height: 0.7rem;">' . $distrito . '</td>
                                            <td colspan="2" style="height: 0.7rem;">' . $celular . '</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="3" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 1.125rem;" colspan="15"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table width="100%" class="table" style="text-align: center;">
                                    <tbody>
                                        <tr>
                                            <td colspan="1" class="bg-gray">Datos</td>
                                            <td colspan="4" class="bg-gray">Padre</td>
                                            <td colspan="4" class="bg-gray">Madre</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="bg-gray">Apellidos y nombres</td>
                                            <td colspan="4">' . ($parentesco == 'PADRE' ? $apoderado : '') . '</td>
                                            <td colspan="4">' . ($parentesco == 'MADRE' ? $apoderado : '') . '</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="bg-gray">Vive</td>
                                            <td colspan="1" class="bg-gray">Si</td>
                                            <td colspan="1" class="box-1">' . ($parentesco == 'PADRE' ? ($vive == 1 ? 'X' : '') : '') . '</td>
                                            <td colspan="1" class="bg-gray">No</td>
                                            <td colspan="1" class="box-1">' . ($parentesco == 'PADRE' ? ($vive == 0 ? 'X' : '') : '') . '</td>
                                            <td colspan="1" class="bg-gray">Si</td>
                                            <td colspan="1" class="box-1">' . ($parentesco == 'MADRE' ? ($vive == 1 ? 'X' : '') : '') . '</td>
                                            <td colspan="1" class="bg-gray">No</td>
                                            <td colspan="1" class="box-1">' . ($parentesco == 'MADRE' ? ($vive == 0 ? 'X' : '') : '') . '</td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2" colspan="1" class="bg-gray">Fecha de Nacimiento</td>
                                            <td colspan="1" class="bg-gray">Dia</td>
                                            <td colspan="1" class="bg-gray">Mes</td>
                                            <td colspan="2" class="bg-gray">Año</td>
                                            <td colspan="1" class="bg-gray">Dia</td>
                                            <td colspan="1" class="bg-gray">Mes</td>
                                            <td colspan="2" class="bg-gray">Año</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;">' . ($parentesco == 'PADRE' ? ($fechaNacimientoApo[2] == '' ? '' : $fechaNacimientoApo[2]) : '') . '</td>
                                            <td colspan="1" style="height: 0.7rem;">' . ($parentesco == 'PADRE' ? ($fechaNacimientoApo[1] == '' ? '' : $fechaNacimientoApo[1]) : '') . '</td>
                                            <td colspan="2" style="height: 0.7rem;">' . ($parentesco == 'PADRE' ? ($fechaNacimientoApo[0] == '' ? '' : $fechaNacimientoApo[0]) : '') . '</td>
                                            <td colspan="1" style="height: 0.7rem;">' . ($parentesco == 'MADRE' ? ($fechaNacimientoApo[2] == '' ? '' : $fechaNacimientoApo[2]) : '') . '</td>
                                            <td colspan="1" style="height: 0.7rem;">' . ($parentesco == 'MADRE' ? ($fechaNacimientoApo[1] == '' ? '' : $fechaNacimientoApo[1]) : '') . '</td>
                                            <td colspan="2" style="height: 0.7rem;">' . ($parentesco == 'MADRE' ? ($fechaNacimientoApo[0] == '' ? '' : $fechaNacimientoApo[0]) : '') . '</td>
                                      </tr>
                                        <tr>
                                            <td colspan="1" class="bg-gray">Ocupación</td>
                                            <td colspan="4"></td>
                                            <td colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="bg-gray">Vive con el Estudiante</td>
                                            <td colspan="1" class="bg-gray">Si</td>
                                            <td colspan="1" class="box-1">' . ($parentesco == 'PADRE' ? ($vive == 1 ? 'X' : '') : '') . '</td>
                                            <td colspan="1" class="bg-gray">No</td>
                                            <td colspan="1" class="box-1">' . ($parentesco == 'PADRE' ? ($vive == 0 ? 'X' : '') : '') . '</td>
                                            <td colspan="1" class="bg-gray">Si</td>
                                            <td colspan="1" class="box-1">' . ($parentesco == 'MADRE' ? ($vive == 1 ? 'X' : '') : '') . '</td>
                                            <td colspan="1" class="bg-gray">No</td>
                                            <td colspan="1" class="box-1">' . ($parentesco == 'MADRE' ? ($vive == 0 ? 'X' : '') : '') . '</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="bg-gray">Religión</td>
                                            <td colspan="4">' . ($parentesco == 'PADRE' ? 'Católica' : '') . '</td>
                                            <td colspan="4">' . ($parentesco == 'MADRE' ? 'Católica' : '') . '</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 1.125rem;" colspan="9"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" style="margin-bottom: 20.5rem;">
                    <tbody>
                        <tr>
                            <td width="50%"><b>1.1.5 Datos de la situación laboral de los estudiantes que trabajen</b></td>
                            <td width="50%"></td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" class="table" style="text-align: center;">
                                    <tbody>
                                        <tr>
                                            <td rowspan="2" colspan="1" class="bg-gray">Año</td>
                                            <td rowspan="2" colspan="1" class="bg-gray">Edad</td>
                                            <td colspan="8" class="bg-gray">Descripcion de la actividad laboral(4)</td>
                                            <td rowspan="2" colspan="2" class="bg-gray">Horas Semanales de trabajo</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="box-1 bg-gray">OB</td>
                                            <td colspan="1" class="box-1 bg-gray">EM</td>
                                            <td colspan="1" class="box-1 bg-gray">TI</td>
                                            <td colspan="1" class="box-1 bg-gray">E/O</td>
                                            <td colspan="1" class="box-1 bg-gray">TF</td>
                                            <td colspan="1" class="box-1 bg-gray">TH</td>
                                            <td colspan="2" class="box-1 bg-gray">Especificar</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 0.125rem;" colspan="12"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="12" style="border: none; font-size: 10px;text-align: left;">
                                                <b>(1)</b> S: Soltero, C: Casado, V: Viudo, D: Divorciado, Cv: Conviviente
                                                <br>
                                                <b>(2)</b> (Si) si cuenta con partida de nacimiento; (No) no ha sido inscrito en el registro civil
                                                <br>
                                                <b>(4)</b> (OB) Obrero, (EM) Empleado, (TI) Trabajo Independiente, (E/O) Empleador, (TF) Trabaj. Fam. No Remunerado, (TH) Trabaj. Del Hogar
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 1.125rem;" colspan="12"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table width="100%" class="table" style="text-align: center;">
                                    <tbody>
                                        <tr>
                                            <td rowspan="2" colspan="1" class="bg-gray">Año</td>
                                            <td rowspan="2" colspan="1" class="bg-gray">Edad</td>
                                            <td colspan="8" class="bg-gray">Descripcion de la actividad laboral(4)</td>
                                            <td rowspan="2" colspan="2" class="bg-gray">Horas Semanales de trabajo</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="box-1 bg-gray">OB</td>
                                            <td colspan="1" class="box-1 bg-gray">EM</td>
                                            <td colspan="1" class="box-1 bg-gray">TI</td>
                                            <td colspan="1" class="box-1 bg-gray">E/O</td>
                                            <td colspan="1" class="box-1 bg-gray">TF</td>
                                            <td colspan="1" class="box-1 bg-gray">TH</td>
                                            <td colspan="2" class="box-1 bg-gray">Especificar</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="1" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                            <td colspan="2" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 0.125rem;" colspan="12"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="12" style="border: none; font-size: 10px;text-align: left;">
                                                <b>(1)</b> S: Soltero, C: Casado, V: Viudo, D: Divorciado, Cv: Conviviente
                                                <br>
                                                <b>(2)</b> (Si) si cuenta con partida de nacimiento; (No) no ha sido inscrito en el registro civil
                                                <br>
                                                <b>(4)</b> (OB) Obrero, (EM) Empleado, (TI) Trabajo Independiente, (E/O) Empleador, (TF) Trabaj. Fam. No Remunerado, (TH) Trabaj. Del Hogar
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 1.125rem;" colspan="12"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" style="margin-bottom: 2.5rem;">
                    <tbody>
                        <tr>
                            <td colspan="3">
                                <b>2. Datos de la Escolaridad del Estudiante</b>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%"><b>2.1 Matricula</b></td>
                            <td width="85%"></td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" class="table" style="text-align: left; font-size: 10px;">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" class="bg-gray"><b>Datos / Años</b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Nombre de la IE</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Código Modular</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Departamento</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Provincia</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Distrito</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Instancia de Gestion Educativa</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Nivel</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Modalidad(1)</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Programa(Sólo EBA)(2)</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Ciclo(Sólo EBA)(3)</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Forma(4)</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Grado</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Sección</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Turno(5)</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Situación final(6)</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Año Lectivo</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Recuperación Pedagógica</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 1.125rem;" colspan="2"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table width="100%" class="table" style="text-align: center;font-size: 10px;">
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="bg-gray">' . (intval($año->año_descripcion) + 0) . '</td>
                                            <td colspan="5" class="bg-gray">' . (intval($año->año_descripcion) + 1) . '</td>
                                            <td colspan="5" class="bg-gray">' . (intval($año->año_descripcion) + 2) . '</td>
                                            <td colspan="5" class="bg-gray">' . (intval($año->año_descripcion) + 3) . '</td>
                                            <td colspan="5" class="bg-gray">' . (intval($año->año_descripcion) + 4) . '</td>
                                            <td colspan="5" class="bg-gray">' . (intval($año->año_descripcion) + 5) . '</td>
                                            <td colspan="5" class="bg-gray">' . (intval($año->año_descripcion) + 6) . '</td>
                                            <td colspan="5" class="bg-gray">' . (intval($año->año_descripcion) + 7) . '</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="height: 0.7rem;">' . $institucion->ie_nombre . '</td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="height: 0.7rem;">' . $institucion->ie_codigo_modular . '</td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="height: 0.7rem;">' . $institucion->departamento->departamento . '</td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="height: 0.7rem;">' . $institucion->provincia->provincia . '</td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="height: 0.7rem;">' . $institucion->distrito->distrito . '</td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="height: 0.7rem;">' . $institucion->ie_ugel . '</td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="height: 0.7rem;">' . $institucion->ie_nivel . '</td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="height: 0.7rem;">EBR</td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="height: 0.7rem;">ESCOLARIZADO</td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="height: 0.7rem;">' . ($grado) . '</td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="height: 0.7rem;">' . ($seccion) . '</td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="height: 0.7rem;">' . $institucion->ie_turno . '</td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                            <td colspan="5" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="box-1">A</td>
                                            <td colspan="1" class="box-1">RR</td>
                                            <td colspan="1" class="box-1">D</td>
                                            <td colspan="1" class="box-1">R</td>
                                            <td colspan="1" class="box-1">P</td>
                                            <td colspan="1" class="box-1">A</td>
                                            <td colspan="1" class="box-1">RR</td>
                                            <td colspan="1" class="box-1">D</td>
                                            <td colspan="1" class="box-1">R</td>
                                            <td colspan="1" class="box-1">P</td>
                                            <td colspan="1" class="box-1">A</td>
                                            <td colspan="1" class="box-1">RR</td>
                                            <td colspan="1" class="box-1">D</td>
                                            <td colspan="1" class="box-1">R</td>
                                            <td colspan="1" class="box-1">P</td>
                                            <td colspan="1" class="box-1">A</td>
                                            <td colspan="1" class="box-1">RR</td>
                                            <td colspan="1" class="box-1">D</td>
                                            <td colspan="1" class="box-1">R</td>
                                            <td colspan="1" class="box-1">P</td>
                                            <td colspan="1" class="box-1">A</td>
                                            <td colspan="1" class="box-1">RR</td>
                                            <td colspan="1" class="box-1">D</td>
                                            <td colspan="1" class="box-1">R</td>
                                            <td colspan="1" class="box-1">P</td>
                                            <td colspan="1" class="box-1">A</td>
                                            <td colspan="1" class="box-1">RR</td>
                                            <td colspan="1" class="box-1">D</td>
                                            <td colspan="1" class="box-1">R</td>
                                            <td colspan="1" class="box-1">P</td>
                                            <td colspan="1" class="box-1">A</td>
                                            <td colspan="1" class="box-1">RR</td>
                                            <td colspan="1" class="box-1">D</td>
                                            <td colspan="1" class="box-1">R</td>
                                            <td colspan="1" class="box-1">P</td>
                                            <td colspan="1" class="box-1">A</td>
                                            <td colspan="1" class="box-1">RR</td>
                                            <td colspan="1" class="box-1">D</td>
                                            <td colspan="1" class="box-1">R</td>
                                            <td colspan="1" class="box-1">P</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                            <td colspan="1" class="box-1"></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 1.125rem;" colspan="40"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" style="margin-bottom: 2.5rem;">
                    <tbody>
                        <tr>
                            <td width="100%"><b>2.2 Traslados</b></td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" class="table" style="text-align: center;">
                                    <tbody>
                                        <tr>
                                            <td colspan="3" class="bg-gray">Fecha</td>
                                            <td colspan="5" class="bg-gray">Motivo de translado</td>
                                            <td colspan="14" class="bg-gray">Institución Educativa de Destino</td>
                                            <td colspan="2" class="bg-gray">V° B° de Traslados</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="bg-gray">Día</td>
                                            <td colspan="1" class="bg-gray">Mes</td>
                                            <td colspan="1" class="bg-gray">Año</td>
                                            <td colspan="5" class="bg-gray">Descripción</td>
                                            <td colspan="7" class="bg-gray">Código Modular</td>
                                            <td colspan="7" class="bg-gray">Nombre</td>
                                            <td colspan="2" class="bg-gray">Firma y Post firma del Director de la I.E que autoriza el traslado</td>
                                        </tr>
                                        <tr>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td colspan="5"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td colspan="7"></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td colspan="5"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td colspan="7"></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td colspan="5"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td colspan="7"></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td colspan="5"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td colspan="7"></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td colspan="5"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td colspan="7"></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td colspan="5"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td colspan="7"></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td colspan="5"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td class="box-1" colspan="1"></td>
                                            <td colspan="7"></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 1.125rem;" colspan="24"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" style="margin-bottom: 5.5rem;">
                    <tbody>
                        <tr>
                            <td colspan="3">
                                <b>3. Responsable de la Matricula en la Institcuión Educativa y Fecha </b>
                            </td>
                        </tr>
                        <tr>
                            <td width="10%"></td>
                            <td width="90%"></td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" class="table" style="text-align: left; font-size: 10px;">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" class="bg-gray"><b>Datos / Años</b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray" style="height: 2.125rem;">Fecha</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Apellidos y Nonbres</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Cargo</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 1.125rem;" colspan="2"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table width="100%" class="table" style="text-align: center;font-size: 10px;">
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 0) . '</td>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 1) . '</td>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 2) . '</td>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 3) . '</td>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 4) . '</td>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 5) . '</td>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 6) . '</td>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 7) . '</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 1.125rem;" colspan="48"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" style="margin-bottom: 2.5rem;">
                    <tbody>
                        <tr>
                            <td colspan="3">
                                <b>4. Datos del Representante Legal </b>
                            </td>
                        </tr>
                        <tr>
                            <td width="10%"></td>
                            <td width="90%"></td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" class="table" style="text-align: left; font-size: 10px;">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" class="bg-gray"><b>Datos / Años</b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Apellidos Paterno</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Apellidos Materno</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Nombres</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Parentesco</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray" style="height: 2.125rem;">Fecha de nacimiento</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Grado de Instrucc.</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Ocupación</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Domicilio</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Teléfono</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 1.125rem;" colspan="2"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table width="100%" class="table" style="text-align: center;font-size: 10px">
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 0) . '</td>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 1) . '</td>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 2) . '</td>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 3) . '</td>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 4) . '</td>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 5) . '</td>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 6) . '</td>
                                            <td colspan="6" class="bg-gray">' . (intval($año->año_descripcion) + 7) . '</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="height: 0.7rem;">' . ($parentesco == 'TUTOR' ? $apoderadoApellidos[0]  : '') . '</td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="height: 0.7rem;">' . ($parentesco == 'TUTOR' ? $apoderadoApellidos[1]  : '') . '</td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="height: 0.7rem;">' . ($parentesco == 'TUTOR' ? $apoderadoNombre  : '') . '</td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="height: 0.7rem;">TUTOR</td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                            <td colspan="2" class="box-1 bg-gray">Dia</td>
                                            <td colspan="2" class="box-1 bg-gray">Mes</td>
                                            <td colspan="2" class="box-1 bg-gray">Año</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="box-1">' . ($parentesco == 'TUTOR' ? ($fechaNacimientoApo[2] == '' ? '' : $fechaNacimientoApo[2]) : '') . '</td>
                                            <td colspan="2" class="box-1">' . ($parentesco == 'TUTOR' ? ($fechaNacimientoApo[1] == '' ? '' : $fechaNacimientoApo[1]) : '') . '</td>
                                            <td colspan="2" class="box-1">' . ($parentesco == 'TUTOR' ? ($fechaNacimientoApo[0] == '' ? '' : $fechaNacimientoApo[0]) : '') . '</td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                            <td colspan="2" class="box-1"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="height: 0.7rem;">' . $Apodireccion . '</td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" style="height: 0.7rem;">' . $Apocelular . '</td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                            <td colspan="6" style="height: 0.7rem;"></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 1.125rem;" colspan="48"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" style="margin-bottom: 2.5rem;">
                    <tbody>
                        <tr>
                            <td colspan="3">
                                <b>5. Supervivencia de los Padres</b>
                            </td>
                        </tr>
                        <tr>
                            <td width="10%"></td>
                            <td width="90%"></td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" class="table" style="text-align: left; font-size: 10px;">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" class="bg-gray"><b>Vive</b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Padre</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="bg-gray">Madre</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 0.125rem;" colspan="2"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table width="100%" class="table" style="text-align: center;font-size: 10px">
                                    <tbody>
                                        <tr>
                                            <td colspan="8" class="bg-gray">' . (intval($año->año_descripcion) + 0) . '</td>
                                            <td colspan="8" class="bg-gray">' . (intval($año->año_descripcion) + 1) . '</td>
                                            <td colspan="8" class="bg-gray">' . (intval($año->año_descripcion) + 2) . '</td>
                                            <td colspan="8" class="bg-gray">' . (intval($año->año_descripcion) + 3) . '</td>
                                            <td colspan="8" class="bg-gray">' . (intval($año->año_descripcion) + 4) . '</td>
                                            <td colspan="8" class="bg-gray">' . (intval($año->año_descripcion) + 5) . '</td>
                                            <td colspan="8" class="bg-gray">' . (intval($año->año_descripcion) + 6) . '</td>
                                            <td colspan="8" class="bg-gray">' . (intval($año->año_descripcion) + 7) . '</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 ">' . ($parentesco == 'PADRE' ? ($vive == 1 ? 'X' : '') : '') . '</td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 ">' . ($parentesco == 'PADRE' ? ($vive == 0 ? 'X' : '') : '') . '</td>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 "></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 ">' . ($parentesco == 'MADRE' ? ($vive == 1 ? 'X' : '') : '') . '</td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 ">' . ($parentesco == 'MADRE' ? ($vive == 0 ? 'X' : '') : '') . '</td>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">Si</td>
                                            <td colspan="2" class="box-1 "></td>
                                            <td colspan="2" class="box-1 bg-gray">No</td>
                                            <td colspan="2" class="box-1 "></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; height: 0.125rem;" colspan="64"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12" style="border: none; font-size: 10px;text-align: left;">
                                <b>(1) Modalidad:</b> (EBR) Edu. Básica Regular, (EBR-AD) Edu. Básica Regular a Distancia, (EBA) Edu. Básica Alternativa, (EBE) Edu. Básica Especial
                                <br>
                                <b>(2) Programa:</b> (PA) Programa de Alfabetización, (PBJ) Prog. de Educ. Bás. Alter. de Jóvenes y Adultos
                                <br>
                                <b>(3) Ciclo:</b> Para el caso de EBA: (IN) Inicial, (INT) Intermedio, (AV) Avanzado
                                <br>
                                <b>(4) Forma:</b> (Esc) Escolarizado, (NoEsc) No Escolarizado; Para el caso de EBA: (P) Presencial, (SP) Semi Presencial, (AD) A distancia
                                <br>
                                <b>(5) Turno:</b> (M) Mañana, (T) Tarde, (N) Noche
                                <br>
                                <b>(6) Situación Final:</b> (Marcar "X" donde corresponda) (A) Aprobado, (RR) Requiere Recuperación, (D) Desaprobado, (R) Retirado; Para el caso de EBA: (RR) Requiere Recuperación, (P) Promovido
                            </td>
                        </tr>
                    </tbody>
                </table>
            </body>
            </html>
        ');

        $dompdf->render();

        $contenido = $dompdf->output();

        $exists = Storage::disk('FichaMatricula')->exists($nombreDocumento);
        if (!$exists) {
            Storage::disk('FichaMatricula')->put($nombreDocumento, $contenido);
        } else {
            Storage::disk('FichaMatricula')->delete($nombreDocumento);
            Storage::disk('FichaMatricula')->put($nombreDocumento, $contenido);
        }
        $alumno = Alumno::find($Persona->alumno->alu_id);
        $alumno->name_ficha_matricula = $nombreDocumento;
        $alumno->save();

        return response()->file(storage_path('app/public/FichaMatricula/' . $nombreDocumento));
    }


    public function generarLibretaNotas(Request $request)
    {
        $data = $request->per_id;

        $institucion = Institucion::where('ie_id', 1)->first();
        $Persona = Persona::where('per_id', $data)->first();
        $alumno = $Persona["per_nombres"] . " " . $Persona["per_apellidos"];
        $dni = $Persona["per_dni"];
        $idNivel  = $Persona->alumno->ultimaMatricula?->gsa?->niv_id; //TODO $data["idNivel"];
        $idGrado  = $Persona->alumno->ultimaMatricula?->gsa?->gra_id; //TODO $data["idGrado"];

        $grado = $Persona->alumno->ultimaMatricula?->gsa?->grado?->gra_descripcion; //TODO
        $personalAcademicoId = PersonalAcademico::where('niv_id', $idNivel)->first();

        $seccion = $Persona->alumno->ultimaMatricula?->gsa?->seccion?->sec_descripcion; //TODO

        // IMPORTANTE: Obtener TODOS los cursos del grado primero
        $todosCursos = Curso::where('gra_id', $idGrado)
            ->where('niv_id', $idNivel)
            ->get();

        // Crear un array para almacenar los datos de cada curso
        $cursos = [];

        // Inicializar todos los cursos con estructura básica vacía y obtener capacidades para cada uno
        foreach ($todosCursos as $curso) {
            $cursoNombre = $curso->cur_nombre;
            $cursos[$cursoNombre] = [
                'capacidades' => [],
                'bimestres' => [],
                'promedio_final' => '',
                'promedio_calculado' => false // Flag para evitar cálculos repetidos
            ];

            // NUEVO: Obtener capacidades para este curso desde la base de datos
            $capacidadesCurso = Capacidad::where('cur_id', $curso->cur_id)->get();
            foreach ($capacidadesCurso as $index => $cursoCapacidad) {
                $capacidad = Capacidad::where('cap_id', $cursoCapacidad->cap_id)->first();
                if ($capacidad) {
                    $cursos[$cursoNombre]['capacidades'][$index] = [
                        'id' => $capacidad->cap_id,
                        'nombre' => $capacidad->cap_descripcion
                    ];
                }
            }

            // Si no se encontraron capacidades, intentar obtener capacidades estándar o genéricas
            if (count($cursos[$cursoNombre]['capacidades']) == 0) {
                // Podemos configurar capacidades genéricas basadas en el área curricular
                // Esta parte dependerá de la estructura de tu base de datos
                // Ejemplo:
                $capacidadesGenericas = Capacidad::where('niv_id', $idNivel)
                    ->where('tipo', 'generica')
                    ->take(3)  // Tomar las primeras 3 capacidades genéricas
                    ->get();

                foreach ($capacidadesGenericas as $index => $capacidad) {
                    $cursos[$cursoNombre]['capacidades'][$index] = [
                        'id' => $capacidad->cap_id,
                        'nombre' => $capacidad->cap_descripcion
                    ];
                }

                // Si aún no hay capacidades, crear algunas predeterminadas
                if (count($cursos[$cursoNombre]['capacidades']) == 0) {
                    $cursos[$cursoNombre]['capacidades'] = [
                        [
                            'id' => 'c1',
                            'nombre' => 'Construye interpretaciones históricas'
                        ],
                        [
                            'id' => 'c2',
                            'nombre' => 'Gestiona responsablemente el espacio y el ambiente'
                        ],
                        [
                            'id' => 'c3',
                            'nombre' => 'Gestiona responsablemente los recursos económicos'
                        ]
                    ];
                }
            }
        }

        // Funciones para convertir entre letras y números
        function convertirLetraANumero($letra)
        {
            switch (trim(strtoupper($letra))) {
                case 'AD':
                    return 4;
                case 'A':
                    return 3;
                case 'B':
                    return 2;
                case 'C':
                    return 1;
                default:
                    return 0; // En caso de valor no reconocido
            }
        }

        function convertirNumeroALetra($numero)
        {
            if ($numero >= 3.5) return 'AD';
            if ($numero >= 2.5) return 'A';
            if ($numero >= 1.5) return 'B';
            return 'C';
        }

        // Obtener todas las notas del alumno
        $notas = Nota::where('alu_id', $Persona->alumno->alu_id)->get();

        // Procesar cada nota para llenar los cursos que sí tienen datos
        foreach ($notas as $value) {
            $curso = Curso::where('cur_nombre', $value->curso->cur_nombre)
                ->where('gra_id', $idGrado)
                ->where('niv_id', $idNivel)
                ->first();

            if ($curso) {
                $cursoNombre = $curso->cur_nombre;

                $notasCapacidades = NotaCapacidad::where('nt_id', $value->nt_id)->get();
                // No sobreescribimos las capacidades aquí, ya las hemos obtenido antes
                $bimestre = $value->nt_bimestre;

                // Inicializar array de notas para este bimestre si no existe
                if (!isset($cursos[$cursoNombre]['bimestres'][$bimestre])) {
                    $cursos[$cursoNombre]['bimestres'][$bimestre] = [
                        'capacidades' => [],
                        'promedio' => ''
                    ];
                }

                // Guardar notas de capacidades
                foreach ($notasCapacidades as $index => $capacidad) {
                    // Asegurar que el índice existe en el array de capacidades
                    if (isset($cursos[$cursoNombre]['capacidades'][$index])) {
                        $cursos[$cursoNombre]['bimestres'][$bimestre]['capacidades'][$index] =
                            !empty($capacidad->nc_nota) ? $capacidad->nc_nota : '';
                    }
                }

                // Guardar promedio bimestral
                $cursos[$cursoNombre]['bimestres'][$bimestre]['promedio'] =
                    !empty($value->nt_nota) ? $value->nt_nota : '';

                // Calcular el promedio final para cada curso con todas las notas disponibles
                // Esto se ejecuta para cada nota, así que usamos un flag para evitar cálculos repetidos
                if (!isset($cursos[$cursoNombre]['promedio_calculado']) || $cursos[$cursoNombre]['promedio_calculado'] === false) {
                    // Obtener todas las notas del alumno para este curso específico
                    $notasFinales = Nota::where('alu_id', $Persona->alumno->alu_id)
                        ->where('pa_id', $value->pa_id)
                        ->get();

                    $suma = 0;
                    $total = $notasFinales->count();
                    $notasValidas = 0;

                    if ($total > 0) {
                        foreach ($notasFinales as $notaFinal) {
                            $notaLetra = $notaFinal->nt_nota;

                            // Solo procesar si la nota tiene un valor
                            if (!empty($notaLetra)) {
                                $valorNumerico = convertirLetraANumero($notaLetra);
                                $suma += $valorNumerico;
                                $notasValidas++;
                            }
                        }

                        // Calcular el promedio solo si hay notas válidas
                        if ($notasValidas > 0) {
                            $promedioNumerico = $suma / $notasValidas;
                            $promedioLetra = convertirNumeroALetra($promedioNumerico);
                            $cursos[$cursoNombre]['promedio_final'] = $promedioLetra;
                        } else {
                            $cursos[$cursoNombre]['promedio_final'] = '';
                        }
                    } else {
                        $cursos[$cursoNombre]['promedio_final'] = '';
                    }

                    // Marcar este curso como ya calculado
                    $cursos[$cursoNombre]['promedio_calculado'] = true;
                }
            }
        }

        // Preparar datos para la vista
        $datosCursos = [];
        foreach ($cursos as $nombreCurso => $curso) {
            $datosCursos[$nombreCurso] = [
                'capacidades' => $curso['capacidades'],
                'bimestres' => isset($curso['bimestres']) ? $curso['bimestres'] : [],
                'promedio_final' => isset($curso['promedio_final']) ? $curso['promedio_final'] : ''
            ];
        }

        // Obteniendo imagen y convirtiendolo a base64
        $cao = public_path('insig.png');
        $educacion = public_path('escudoMinedu.png');
        $sello = public_path('sello.png');
        $contenidoBinario1 = file_get_contents($educacion);
        $contenidoBinario2 = file_get_contents($cao);
        $contenidoBinario3 = file_get_contents($sello);
        $imagenEducacion = base64_encode($contenidoBinario1);
        $imagenCAO = base64_encode($contenidoBinario2);
        $imagenSello = base64_encode($contenidoBinario3);

        $nombreDocumento = "LIBRETA_NOTAS_" . str_replace(' ', '_', $alumno) . ".pdf";

        // Generando HTML del documento
        $html = $this->generarHtmlLibreta($alumno, $grado, $seccion, $datosCursos, $imagenEducacion, $imagenCAO, $imagenSello, $institucion, $dni);

        // Creando Pdf
        include_once "../vendor/autoload.php";
        $dompdf = new Dompdf();
        $dompdf->setPaper('A3', 'landscape');
        $dompdf->loadHtml($html);
        $dompdf->render();
        $contenido = $dompdf->output();

        // Aquí puedes devolver el PDF o guardarlo
        return response($contenido)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $nombreDocumento . '"');
    }

    /**
     * Genera el HTML para la libreta de notas
     */
    private function generarHtmlLibreta($alumno, $grado, $seccion, $datosCursos, $imagenEducacion, $imagenCAO, $imagenSello, $institucion, $dni)
    {
        // Iniciar construcción del HTML
        $html = '<!DOCTYPE html>
                        <html lang="es">
                        <head>
                            <meta charset="UTF-8">
                            <title>Libreta de Notas</title>
                            <style>
                                body {
                                    font-family: Arial, sans-serif;
                                    font-size: 12px;
                                    margin: 0;
                                    padding: 0;
                                }
                                .title {
                                    font-size: 14px;
                                    font-weight: bold;
                                    margin-bottom: 10px;
                                    text-align: center;
                                }
                                .table {
                                    border-collapse: collapse;
                                    width: 100%;
                                    margin-bottom: 15px;
                                }
                                .table th, .table td {
                                    border: 1px solid #000;
                                    padding: 5px;
                                    vertical-align: middle;
                                }
                                .bg-gray {
                                    background-color: #f0f0f0;
                                    font-weight: bold;
                                }
                                .logo {
                                    width: 80px;
                                    height: auto;
                                    max-height: 80px;
                                }
                                .sello {
                                    width: 100px;
                                    height: auto;
                                    max-height: 100px;
                                }
                                .text-center {
                                    text-align: center;
                                }
                                .container {
                                    max-width: 100%;
                                    margin: 0 auto;
                                }
                                .no-wrap {
                                    white-space: nowrap;
                                }
                                .wrap-text {
                                    word-wrap: break-word;
                                    word-break: break-all;
                                }
                                td, th {
                                    line-height: 1.2;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="container">
                                <table width="100%" style="margin-bottom: 1.5rem;">
                                    <tbody>
                                        <tr>
                                            <td align="center" colspan="3" class="title">INFORME DE PROGRESO DEL APRENDIZAJE DEL ESTUDIANTE - ' . date('Y') . '</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table width="100%" style="margin-bottom: 1.5rem;">
                                    <tbody>
                                        <tr>
                                            <td align="center" width="15%">
                                                <img src="data:image/jpg;base64,' . $imagenEducacion . '" alt="escudo" class="logo" />
                                            </td>
                                            <td width="70%">
                                                <table width="100%" border="1" class="table">
                                                    <tr>
                                                        <td class="bg-gray" width="25%">DRE:</td>
                                                        <td width="25%">' . $institucion->ie_dre . '</td>
                                                        <td class="bg-gray" width="25%">UGEL:</td>
                                                        <td width="25%">' . $institucion->ie_ugel . '</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-gray">Nivel:</td>
                                                        <td>PRIMARIA</td>
                                                        <td class="bg-gray">Código Modular:</td>
                                                        <td>' . $institucion->ie_codigo_modular . '</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-gray">Institución Educativa:</td>
                                                        <td colspan="3">' . $institucion->ie_nombre . '</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-gray">Grado:</td>
                                                        <td>' . $grado . '</td>
                                                        <td class="bg-gray">Sección:</td>
                                                        <td>' . $seccion . '</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-gray">Apellidos y nombres del estudiante:</td>
                                                        <td colspan="3">' . $alumno . '</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bg-gray">Código del estudiante:</td>
                                                        <td >' . '</td>
                                                        <td class="bg-gray">DNI:</td>
                                                        <td>' . $dni . '</td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td align="center" width="15%">
                                                <img src="data:image/jpg;base64,' . $imagenCAO . '" alt="cao" class="logo" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>';

        $contadorCursos = 0;
        $html .= '<table width="100%" border="1" class="table" style="margin-bottom: 2rem;">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="bg-gray" width="15%">Área Curricular</th>
                                            <th rowspan="2" class="bg-gray" width="35%">Competencias</th>
                                            <th colspan="4" class="bg-gray" width="40%">Calificativo por periodo</th>
                                            <th rowspan="2" class="bg-gray" width="10%">Calif. final del área</th>
                                        </tr>
                                        <tr>
                                            <th class="bg-gray" width="10%">1</th>
                                            <th class="bg-gray" width="10%">2</th>
                                            <th class="bg-gray" width="10%">3</th>
                                            <th class="bg-gray" width="10%">4</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

        foreach ($datosCursos as $nombreCurso => $curso) {
            $contadorCursos++;
            $numCapacidades = max(1, count($curso['capacidades']));
            $rowspan = $numCapacidades + 1;

            $html .= '<tr>
                                <td rowspan="' . $rowspan . '" style="vertical-align: middle;">' . $nombreCurso . '</td>';

            for ($i = 0; $i < $numCapacidades; $i++) {
                if ($i > 0) $html .= '<tr>';

                $capacidad = isset($curso['capacidades'][$i]) ? $curso['capacidades'][$i]['nombre'] : '';
                $html .= '<td>' . $capacidad . '</td>';

                for ($bimestre = 1; $bimestre <= 4; $bimestre++) {
                    $nota = $curso['bimestres'][$bimestre]['capacidades'][$i] ?? '';
                    $html .= '<td align="center">' . $nota . '</td>';
                }

                if ($i == 0) {
                    $promedioFinal = $curso['promedio_final'] ?? '';
                    $html .= '<td rowspan="' . $rowspan . '" align="center" style="vertical-align: middle;">' . $promedioFinal . '</td>';
                }

                $html .= '</tr>';
            }

            // $html .= '<tr>
            //                     <td class="bg-gray">CALIFICATIVO DE CAPACIDADES</td>';
            // for ($bimestre = 1; $bimestre <= 4; $bimestre++) {
            //     $promedioCap = $curso['bimestres'][$bimestre]['promedio_capacidades'] ?? $curso['bimestres'][$bimestre]['promedio'] ?? '';
            //     $html .= '<td align="center">' . $promedioCap . '</td>';
            // }
            // $html .= '</tr>';

            $html .= '<tr>
                                <td class="bg-gray">CALIFICATIVO DE AREA</td>';
            for ($bimestre = 1; $bimestre <= 4; $bimestre++) {
                $promedio = $curso['bimestres'][$bimestre]['promedio'] ?? '';
                $html .= '<td align="center">' . $promedio . '</td>';
            }
            $html .= '</tr>';

            // 👇 Insertar salto de página cada 3 cursos
            if ($contadorCursos % 4 == 0 && $contadorCursos < count($datosCursos)) {
                $html .= '</tbody></table>';
                $html .= '<div style="page-break-after: always;"></div>';
                $html .= '<table width="100%" border="1" class="table" style="margin-bottom: 2rem;">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" class="bg-gray" width="15%">Área Curricular</th>
                                                    <th rowspan="2" class="bg-gray" width="35%">Competencias</th>
                                                    <th colspan="4" class="bg-gray" width="40%">Calificativo por periodo</th>
                                                    <th rowspan="2" class="bg-gray" width="10%">Calif. final del área</th>
                                                </tr>
                                                <tr>
                                                    <th class="bg-gray" width="10%">1</th>
                                                    <th class="bg-gray" width="10%">2</th>
                                                    <th class="bg-gray" width="10%">3</th>
                                                    <th class="bg-gray" width="10%">4</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
            }
        }



        $html .= '</tbody></table>';

        // // Situación final
        // $html .= '<table width="100%" border="1" class="table" style="margin-bottom: 1rem;">
        //                     <thead>
        //                         <tr>
        //                             <th colspan="3" class="bg-gray">Situación final del estudiante al término del periodo lectivo</th>
        //                         </tr>
        //                         <tr>
        //                             <th width="33%">Promovido de grado</th>
        //                             <th width="33%">Requiere recuperación pedagógica</th>
        //                             <th width="34%">Permanece en el grado</th>
        //                         </tr>
        //                     </thead>
        //                     <tbody>
        //                         <tr>
        //                             <td height="30" align="center"></td>
        //                             <td align="center"></td>
        //                             <td align="center"></td>
        //                         </tr>
        //                     </tbody>
        //                 </table>';

            $html .= '</br></br></br></br></br></br></br></br>';
        // Tabla de conclusión descriptiva por período
        $html .= '<table width="100%" border="1" class="table" style="margin-bottom: 1rem;">
                    <thead>
                        <tr>
                            <th width="15%" class="bg-gray">Período</th>
                            <th width="85%" class="bg-gray">Conclusión descriptiva por período</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td height="30" align="center">1</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td height="30" align="center">2</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td height="30" align="center">3</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td height="30" align="center">4</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>';


        $html .= '</br></br></br></br></br></br></br>';
        // Tabla de resumen de asistencia del estudiante
        $html .= '<h3 style="text-align: center; font-weight: bold; margin: 1rem 0;">RESUMEN DE ASISTENCIA DEL ESTUDIANTE</h3>';
        $html .= '<table width="100%" border="1" class="table" style="margin-bottom: 1rem;">
                    <thead>

                        <tr>
                            <th rowspan="2" width="15%" class="bg-gray">Período</th>
                            <th colspan="2" width="42.5%" class="bg-gray">Inasistencias</th>
                            <th colspan="2" width="42.5%" class="bg-gray">Tardanzas</th>
                        </tr>
                        <tr>
                            <th width="21.25%" class="bg-gray">Justificadas</th>
                            <th width="21.25%" class="bg-gray">Injustificadas</th>
                            <th width="21.25%" class="bg-gray">Justificadas</th>
                            <th width="21.25%" class="bg-gray">Injustificadas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td height="30" align="center">1</td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                        </tr>
                        <tr>
                            <td height="30" align="center">2</td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                        </tr>
                        <tr>
                            <td height="30" align="center">3</td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                        </tr>
                        <tr>
                            <td height="30" align="center">4</td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                        </tr>
                    </tbody>
                </table>';


        // Título para la sección de participación de padres
        $html .= '</br></br></br></br></br></br></br></br>';
        $html .= '<h3 style="text-align: center; font-weight: bold; margin: 1rem 0;">PARTICIPACIÓN DE LOS PADRES DE FAMILIA</h3>';

        // Tabla de criterios de evaluación (participación de padres)
        $html .= '<table width="100%" border="1" class="table" style="margin-bottom: 1rem;">
                    <thead>
                        <tr>
                            <th width="70%" class="bg-gray">CRITERIOS DE EVALUACIÓN</th>
                            <th colspan="4" width="30%" class="bg-gray">BIMESTRE</th>
                        </tr>
                        <tr>
                            <th width="70%"></th>
                            <th width="7.5%" class="bg-gray">I</th>
                            <th width="7.5%" class="bg-gray">II</th>
                            <th width="7.5%" class="bg-gray">III</th>
                            <th width="7.5%" class="bg-gray">IV</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td height="25" style="padding-left: 10px;">1. Mantiene aseado a su niño(a).</td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                        </tr>
                        <tr>
                            <td height="25" style="padding-left: 10px;">2. Se interesa por el aprendizaje de su hijo(a).</td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                        </tr>
                        <tr>
                            <td height="25" style="padding-left: 10px;">3. Envía oportunamente sus materiales (útiles escolares).</td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                        </tr>
                        <tr>
                            <td height="25" style="padding-left: 10px;">4. Participa en actividades en el aula e Institución Educativa.</td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                        </tr>
                        <tr>
                            <td height="25" style="padding-left: 10px;">5. Envía puntualmente a su hijo(a) a la Institución Educativa.</td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                        </tr>
                        <tr>
                            <td height="25" style="padding-left: 10px;">6. Asiste a la Escuela de Padres.</td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                        </tr>
                        <tr>
                            <td height="25" style="padding-left: 10px;">7. Participa en la vigilancia escolar.</td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                        </tr>
                    </tbody>
                </table>';


        $html .= '</br></br></br></br></br>';
        // Título de la escala de calificaciones

        $html .= '<h3 style="text-align: center; font-weight: bold; margin: 1rem 0;">ESCALA DE CALIFICACIONES DEL CNEB</h3>';

        // Tabla de escala de calificaciones del CNEB
        $html .= '<table width="100%" border="1" class="table" style="margin-bottom: 1rem;">
                    <tbody>
                        <tr>
                            <td width="8%" align="center" style="font-weight: bold; vertical-align: middle;">AD</td>
                            <td width="92%" style="padding: 8px;">
                                <strong>Logro destacado</strong><br>
                                Cuando el estudiante evidencia un nivel superior a lo esperado respecto a la competencia. Esto quiere decir que demuestra aprendizajes que van más allá del nivel esperado.
                            </td>
                        </tr>
                        <tr>
                            <td width="8%" align="center" style="font-weight: bold; vertical-align: middle;">A</td>
                            <td width="92%" style="padding: 8px;">
                                <strong>Logro esperado</strong><br>
                                Cuando el estudiante evidencia el nivel esperado respecto a la competencia, demostrando manejo satisfactorio en todas las tareas propuestas y en el tiempo programado.
                            </td>
                        </tr>
                        <tr>
                            <td width="8%" align="center" style="font-weight: bold; vertical-align: middle;">B</td>
                            <td width="92%" style="padding: 8px;">
                                <strong>En proceso</strong><br>
                                Cuando el estudiante está próximo o cerca al nivel esperado respecto a la competencia, para lo cual requiere acompañamiento durante un tiempo razonable para lograrlo.
                            </td>
                        </tr>
                        <tr>
                            <td width="8%" align="center" style="font-weight: bold; vertical-align: middle;">C</td>
                            <td width="92%" style="padding: 8px;">
                                <strong>En inicio</strong><br>
                                Cuando el estudiante muestra un progreso mínimo en una competencia de acuerdo al nivel esperado. Evidencia con frecuencia dificultades en el desarrollo de las tareas, por lo que necesita mayor tiempo de acompañamiento e intervención del docente.
                            </td>
                        </tr>
                    </tbody>
                </table>';

        // $html .= '<table width="100%" border="1" class="table" style="margin-bottom: 3rem;">
        //                     <thead>
        //                         <tr><th colspan="3" class="bg-gray">Área(a) y/o taller(es) que pasan a recuperación pedagógica</th></tr>
        //                     </thead>
        //                     <tbody>
        //                         <tr><td height="30" colspan="3" align="center"></td></tr>
        //                     </tbody>
        //                 </table>';
       // $html .= '</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>';
        $html .= '</br></br></br></br></br></br></br></br></br>';
       $html .= '<table width="100%" style="margin-top: 1rem;">
                            <tbody>
                                <tr>
                                   <td width="50%" align="raight">

                                    </td>
                                    <td width="50%" align="center">
                                        <img src="data:image/jpg;base64,' . $imagenSello . '" alt="sello" class="sello" style="width: 300px" />
                                    </td>

                                </tr>
                                <tr>
                                 <td width="30%" align="center">
                                        <div style="border-top: 1px solid #000; padding-top: 5px; width: 40%; margin: 0 auto;">
                                            DOCENTE DE AULA
                                        </div>
                                    </td>
                                    <td width="30%" align="center">
                                        <div style="border-top: 1px solid #000; padding-top: 5px; width: 40%; margin: 0 auto;">
                                            DIRECTOR
                                        </div>
                                    </td>


                                </tr>
                            </tbody>
                        </table>
                        </div>
                        </body>
            </html>';


        return $html;
    }
}
