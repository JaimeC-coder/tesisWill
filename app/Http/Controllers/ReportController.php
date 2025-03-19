<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Anio;
use App\Models\AsignarCurso;
use App\Models\Curso;
use App\Models\Grado;
use App\Models\Gsa;
use App\Models\Matricula;
use App\Models\Nivel;
use App\Models\Persona;
use App\Models\PersonalAcademico;
use App\Models\Rol;
use App\Models\Seccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Illuminate\Log\log;

class ReportController extends Controller
{
    public function inicio()
    {

        $anios = Anio::where('is_deleted', '!=', 1)->get();
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
        $data = Grado::where('niv_id', $nivel_id)->get();
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
        $anios = Anio::where('is_deleted', '!=', 1)->get();

        $anio = $request->anio;
        $nivel = $request->nivel;
        $grado = $request->grado;
        $seccion = $request->seccion;



        if ($anio == null || $nivel == null || $grado == null || $seccion == null) {
            $result = [];
        } else {
            $result = $this->getCoursesWithAvgsAndNotes($anio, $nivel, $grado, $seccion);
        }

       // return $result;
        return view('view.reporte.gestion', compact('result', 'anios'));
    }





    public function getCoursesWithAvgsAndNotes($year, $level, $grade, $section)
    {

        $result = DB::select('CALL sp_coursesWithAvgsAndNotes(?, ?, ?, ?)', [$year, $level, $grade, $section]);
        return $result;
    }
}
