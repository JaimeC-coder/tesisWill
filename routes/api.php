<?php

use App\Http\Controllers\AnioController;
use App\Http\Controllers\AsignarCursoController;
use App\Http\Controllers\AsignarGradoController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ReportController;

use Illuminate\Support\Facades\Route;


Route::POST('provincia', [DepartamentoController::class, 'searchProvincia']);
Route::POST('distrito', [DepartamentoController::class, 'searchDistrito']);
Route::POST('personas', [PersonaController::class,'searchDni']);
Route::POST('alumnoMatricula', [MatriculaController::class,'searchAlumno']);
Route::POST('showGrados', [MatriculaController::class,'showGrados']);
Route::POST('showSecciones', [MatriculaController::class,'showSecciones']);
Route::POST('infoSecciones', [MatriculaController::class,'infoSecciones']);
Route::POST('asignarCurso', [AsignarCursoController::class,'asignarCurso']);
Route::POST('EliminarCurso', [AsignarCursoController::class,'eliminarCurso']);
Route::POST('asignacionMasivaCurso', [AsignarCursoController::class,'asignacionMasivaCurso']);
Route::POST('EliminacionMasivaCurso', [AsignarCursoController::class,'eliminacionMasivaCurso']);
Route::GET('notas1', [NotaController::class, 'inicioaPI']);

Route::group(['prefix' => 'anio'], function () {
    Route::post('nivel', [AnioController::class, 'nivel']);
    Route::post('grado',[AnioController::class,'grado'] );
    Route::post('seccion', [AnioController::class, 'seccion']);
});

Route::group(['prefix' => 'nota'], function () {
    Route::post('docente', [NotaController::class, 'buscarDocente']);
    Route::post('grado', [NotaController::class, 'buscarGrados']);
    Route::post('curso',[NotaController::class,'getCoursesByTeacher'] );
    //nota/capacidad/actualizar
    Route::post('capacidad/actualizar', [NotaController::class, 'updateCapacidad']);
    // Route::post('seccion', [AnioController::class, 'seccion']);
});




Route::group(['prefix' => 'horario'], function () {
    Route::post('search', [HorarioController::class, 'search']);
    Route::post('register', [HorarioController::class, 'store']);
});
Route::group(['prefix' => 'reporte'], function () {
    Route::post('matricula', [ReportController::class, 'matricula']);
    Route::post('personal', [ReportController::class, 'personal']);
    Route::post('sexo', [ReportController::class, 'sexo']);
    Route::post('countPersonal', [ReportController::class, 'countPersonal']);
    Route::post('vacante', [ReportController::class, 'vacante']);
});
Route::group(['prefix' => 'asignacion'], function () {
    Route::post('listCurso', [AsignarGradoController::class, 'listCurso']);
    Route::post('/masiva/grado', [AsignarGradoController::class, 'masiva']);
    Route::post('eliminacion/masiva/grado', [AsignarGradoController::class, 'eliminacionMasiva']);
});




