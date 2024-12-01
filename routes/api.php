<?php

use App\Http\Controllers\AnioController;
use App\Http\Controllers\AsignarCursoController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\PersonaController;
use App\Models\AsignarCurso;
use Illuminate\Support\Facades\Route;


Route::POST('provincia', [DepartamentoController::class, 'searchProvincia']);
Route::POST('distrito', [DepartamentoController::class, 'searchDistrito']);
Route::POST('personas', [PersonaController::class,'searchDni']);
Route::POST('asignarCurso', [AsignarCursoController::class,'asignarCurso']);
Route::POST('EliminarCurso', [AsignarCursoController::class,'eliminarCurso']);
Route::POST('asignacionMasivaCurso', [AsignarCursoController::class,'asignacionMasivaCurso']);
Route::POST('EliminacionMasivaCurso', [AsignarCursoController::class,'eliminacionMasivaCurso']);


Route::group(['prefix' => 'anio'], function () {

    Route::post('nivel', [AnioController::class, 'nivel']);
    Route::post('grado',[AnioController::class,'grado'] );
    Route::post('seccion', [AnioController::class, 'seccion']);

});
Route::group(['prefix' => 'horario'], function () {

    Route::post('search', [HorarioController::class, 'search']);
    Route::post('register', [HorarioController::class, 'store']);


});


