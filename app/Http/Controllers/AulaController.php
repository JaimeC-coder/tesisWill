<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AulaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $tipoAmbiente = ['Aula de Clases', 'Oficina', 'Extra'];
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $ambiente = new Aula();

        $tipoAmbiente = $this->tipoAmbiente;


        return view('view.ambiente.create', compact('tipoAmbiente', 'ambiente'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        DB::beginTransaction();
        try {

                Aula::create([
                    'ala_descripcion' => $request->descripcion,
                    'ala_tipo' => $request->tipo,
                    'ala_aforo' => $request->aforo,
                    'ala_ubicacion' => $request->ubicacion,
                ]);

            DB::commit();

            return view('view.ambiente.inicio')->with('success', 'Aula creada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('ambiente.create')->with('error', 'Error al crear el aula');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Aula $aula)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aula $ambiente)
    {
        $tipoAmbiente = $this->tipoAmbiente;
        return view('view.ambiente.edit', compact('tipoAmbiente', 'ambiente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aula $ambiente)
    {

        DB::beginTransaction();
        try {
            $ambiente->update([
                'ala_descripcion' => $request->ala_descripcion,
                'ala_tipo' => $request->ala_tipo,
                'ala_aforo' => $request->ala_aforo,
                'ala_ubicacion' => $request->ala_ubicacion,
            ]);

            DB::commit();

            return redirect()->route('ambiente.inicio')->with('success', 'Aula actualizada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return view('view.ambiente.edit')->with('error', 'Error al actualizar el aula');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aula $ambiente)
    {
        DB::beginTransaction();
        try {
            $ambiente->update([
                'ala_is_delete' => 1,
            ]);

            DB::commit();

            return redirect()->route('ambiente.inicio')->with('success', 'Aula eliminada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return view('view.ambiente.inicio')->with('error', 'Error al eliminar el aula');
        }
    }

    public function inicio()
    {
        $aulas = Aula::orderBy('ala_id','asc')->where('ala_is_delete','!=',1)->get();
        return view('view.ambiente.inicio',compact('aulas'));
    }
    public function aulas()
    {
        $aulas = Aula::where('ala_en_uso', '!=', 1)
        ->whereNotIn('ala_tipo', ['Oficina', 'Extra'])
        ->where('ala_is_delete', '!=', 1)
        ->orderBy('ala_id', 'asc')
        ->get();
        return $aulas;
        // return view('ambiente.inicio');
    }
}
