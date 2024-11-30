<?php

use App\Http\Controllers\AsignarCursoController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\PersonaController;
use App\Models\AsignarCurso;
use Illuminate\Support\Facades\Route;


Route::POST('provincia', [DepartamentoController::class, 'searchProvincia']);
Route::POST('distrito', [DepartamentoController::class, 'searchDistrito']);
Route::POST('personas', [PersonaController::class,'searchDni']);
Route::POST('asignarCurso', [AsignarCursoController::class,'asignarCurso']);
Route::POST('EliminarCurso', [AsignarCursoController::class,'eliminarCurso']);
Route::POST('AsignacionMasivaCurso', [AsignarCursoController::class,'asignacionMasivaCurso']);
Route::POST('EliminacionMasivaCurso', [AsignarCursoController::class,'eliminacionMasivaCurso']);
// Route::resource('personalAcademico', 'PersonalAcademicoController');
// Route::resource('roles', 'RolController');

