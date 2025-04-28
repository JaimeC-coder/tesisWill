<?php

namespace App\Http\Controllers;

use App\Models\Anio;
use App\Models\AsignarGrado;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\Seccion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $estado = [1 => 'Activo', 2 => 'Inactivo'];
    public $taller = [1 => 'Si', 2 => 'No'];
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $anio = new Anio();
        $estado = $this->estado;
        $taller = $this->taller;
        $listAnio = $this->ListAnio();

        return view('view.anioEscolar.create', compact('anio', 'estado', 'taller', 'listAnio'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        DB::beginTransaction();
        try {
            Anio::create([
                'anio_descripcion' => $request->anio_descripcion,
                'anio_fechaInicio' => $request->anio_fechaInicio,
                'anio_fechaFin' => $request->anio_fechaFin,
                'anio_duracionHoraAcademica' => $request->anio_duracionHoraAcademica,
                'anio_duracionHoraLibre' => $request->anio_duracionHoraLibre,
                'anio_cantidadPersonal' => $request->anio_cantidadPersonal,
                'anio_tallerSeleccionable' => $request->anio_tallerSeleccionable,
                'anio_estado' => $request->anio_estado
            ]);

            DB::commit();

            return redirect()->route('anio.inicio')->with('success', 'Año escolar creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('anio.create')->with('error', 'Error al crear el aula');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Anio $anio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anio $anio)
    {
        //
        $estado = $this->estado;
        $taller = $this->taller;
        $listAnio = $this->ListAnio();
        return view('view.anioEscolar.edit', compact('anio', 'estado', 'taller', 'listAnio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Anio $anio)
    {
        //
        DB::beginTransaction();
        try {
            $anio->update([
                'anio_descripcion' => $request->anio_descripcion,
                'anio_fechaInicio' => $request->anio_fechaInicio,
                'anio_fechaFin' => $request->anio_fechaFin,
                'anio_duracionHoraAcademica' => $request->anio_duracionHoraAcademica,
                'anio_duracionHoraLibre' => $request->anio_duracionHoraLibre,
                'anio_cantidadPersonal' => $request->anio_cantidadPersonal,
                'anio_tallerSeleccionable' => $request->anio_tallerSeleccionable,
                'anio_estado' => $request->anio_estado
            ]);

            DB::commit();

            return redirect()->route('anio.inicio')->with('success', 'Año escolar actualizado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('anio.edit')->with('error', 'Error al actualizar el año escolar');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anio $anio)
    {

        DB::beginTransaction();
        try {
            $anio->update([
                'is_deleted' => 1
            ]);

            DB::commit();

            return redirect()->route('anio.inicio')->with('success', 'Año escolar eliminado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('anio.inicio')->with('error', 'Error al eliminar el año escolar');
        }
    }

    public function inicio()
    {
        $anio = Anio::where('anio_estado', '!=', 0)->orderBy('anio_id', 'desc')->get();
        return view('view.anioEscolar.inicio', compact('anio'));
    }

    public function nivel()
    {
        try {
            $nivel = Nivel::all();
            return response()->json($nivel);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function grado(Request $request)
    {

        try {


            $nivel = $request->nivel_id;
            $user = $request->user_id ?? null;

            $user = User::Where('per_id', $user)
                ->where('is_deleted', '!=', 1)
                ->first();

            if ($user->roles[0]->name == "Docente") {
                $grado = AsignarGrado::where('pa_id', $user->persona->personalAcademico->pa_id)
                    ->where('asig_is_deleted', 0)
                    ->with(['grado' => function ($query) use ($nivel) {
                        $query->where('niv_id', $nivel);
                    }])
                    ->get()->map(function ($item) {
                        $gradoData = $item->grado ? $item->grado->toArray() : [];
                        unset($item->grado); // Quitamos el objeto 'grado'
                        return array_merge($item->toArray(), $gradoData); // Combinamos en un solo nivel
                    });
            } else {
                $grado = Grado::where('niv_id', $nivel)->get();
            }

            Log::info('grado');
            Log::info($grado);


            return response()->json($grado);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function seccion(Request $request)
    {
        try {


            $grado = $request->grado_id;
            $user = $request->user_id ?? null;

            $user = User::Where('per_id', $user)
                ->where('is_deleted', '!=', 1)
                ->first();

            if ($user->roles[0]->name == "Docente") {
                $seccion = Seccion::where('sec_tutor', $user->persona->personalAcademico->pa_id)
                    ->where('sec_is_delete', 0)
                    ->get();
                Log::info($seccion);
            } else {
                $seccion = Seccion::where('gra_id', $request->grado_id)->get();
                Log::info('seccion');
                Log::info($seccion);
            }
            Log::info('seccionsalida ');
            return response()->json($seccion);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }



    public function ListAnio()
    {

        $anioActual = Carbon::now()->format('Y');
        $anio = Anio::where('anio_estado', '!=', 0)
            ->where('is_deleted', 0)
            ->pluck('anio_descripcion');
        $anioBase = $anioActual + 5;

        $listAnio = [];
        for ($i = $anioActual; $i <= $anioBase; $i++) {
            //quiero que busques el varo del año que se esta recorriendo en mi lista
            if (!$anio->contains($i)) {
                $listAnio[] = $i;
            }
        }

        return $listAnio;
    }
}
