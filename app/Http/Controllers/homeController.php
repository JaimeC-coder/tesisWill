<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Aula;
use App\Models\Matricula;
use App\Models\Periodo;
use App\Models\PersonalAcademico;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $periodo = Periodo::where('is_deleted', '!=', 1)->count();
        $usuarios = User::where('is_deleted', '!=', 1)->count();
        $aulas = Aula::where('ala_is_delete', '!=', 1)->count();
        $docentes = PersonalAcademico::where('is_deleted', '!=', 1)->count();
        $alumnos = Alumno::where('is_deleted', '!=', 1)->count();
        $matriculas = Matricula::where('is_deleted', '!=', 1)->count();

        $informacion =[
            [
                'title' => 'Periodos',
                'value' => $periodo,
                'icon' => 'fas fa-calendar-alt',
                'color' => 'bg-primary',
                'route' => 'periodos.index'
            ],
            [
                'title' => 'Usuarios',
                'value' => $usuarios,
                'icon' => 'fas fa-users',
                'color' => 'bg-success',
                'route' => 'usuarios.index'
            ],
            [
                'title' => 'Aulas',
                'value' => $aulas,
                'icon' => 'fas fa-chalkboard-teacher',
                'color' => 'bg-info',
                'route' => 'aulas.index'
            ],
            [
                'title' => 'Docentes',
                'value' => $docentes,
                'icon' => 'fas fa-chalkboard-teacher',
                'color' => 'bg-warning',
                'route' => 'docentes.index'
            ],
            [
                'title' => 'Alumnos',
                'value' => $alumnos,
                'icon' => 'fas fa-user-graduate',
                'color' => 'bg-danger',
                'route' => 'alumnos.index'
            ],
            [
                'title' => 'Matriculas',
                'value' => $matriculas,
                'icon' => 'fas fa-user-graduate',
                'color' => 'bg-secondary',
                'route' => 'matriculas.index'
            ],
        ];



        return view('home', compact('informacion'));
    }
}
