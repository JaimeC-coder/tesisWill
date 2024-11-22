<?php

namespace App\Http\Controllers;

use App\Models\Nivel;
use App\Models\Persona;
use App\Models\PersonalAcademico;
use App\Models\Rol;
use Illuminate\Http\Request;

class PersonalAcademicoController extends Controller
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
    public function show(PersonalAcademico $personalAcademico)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PersonalAcademico $personalAcademico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PersonalAcademico $personalAcademico)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonalAcademico $personalAcademico)
    {
        //
    }
    public function inicio()
    {
        $personal = PersonalAcademico::where('is_deleted','!=',1)->orderBy('pa_id', 'desc')->get();

        return view('view.personalAcademico.inicio', compact('personal'));
    }
}
