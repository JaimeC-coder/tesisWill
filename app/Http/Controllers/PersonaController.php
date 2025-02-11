<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class PersonaController extends Controller
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
    public function show(Persona $persona)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Persona $persona)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Persona $persona)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persona $persona)
    {
        //
    }

    public function searchDni(Request $request)
    {
        try {
            $persona = $request->per_dni;

            $info = $this->searchDNIDB($persona);
            //responnse existe en la base de datos
            if ($info) {
                return response()->json($info);

            }
            $info = $this->searchDNIApi($persona);
            //response existe en la api
            if ($info->status() == 200) {
                return response()->json($info->json());
            }

        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
    public function searchDNIApi($dni)
    {
        try {
            $datos = Http::get('https://dniruc.apisperu.com/api/v1/dni/'.$dni.'?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6InNhbXllc2h1YTcyN0BnbWFpbC5jb20ifQ.0z14bKT2JWPsbs2y9j40RWrW_RvG9XaXtwUh2MRGOyQ');

            return $datos;
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
    public function searchDNIDB($dni)
    {
        try {
            $persona = Persona::where('per_dni', $dni)->first();
            return $persona;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
