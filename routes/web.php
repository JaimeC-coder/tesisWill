<?php


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return redirect()->to('landing');
    return view('welcome');
});
Route::get('/landing', function () {
    return view('welcome');
});

Auth::routes();

//auth routes

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/matricula/inicio', [App\Http\Controllers\MatriculaController::class, 'inicio'])->name('matricula.inicio');
    Route::resource('/matricula', App\Http\Controllers\MatriculaController::class);

    Route::get('/ambiente/inicio', [App\Http\Controllers\AulaController::class, 'inicio'])->name('ambiente.inicio');
    Route::resource('/ambiente', App\Http\Controllers\AulaController::class);

    Route::get('/personal/inicio', [App\Http\Controllers\PersonalAcademicoController::class, 'inicio'])->name('personal.inicio');
    Route::resource('/personal',App\Http\Controllers\PersonalAcademicoController::class);

    Route::get('/escolar/anio/inico', [App\Http\Controllers\AnioController::class, 'inicio'])->name('anio.inicio');
    Route::resource('/escolar/anio', App\Http\Controllers\AnioController::class);

    Route::get('/periodo/inicio', [App\Http\Controllers\PeriodoController::class, 'inicio'])->name('periodo.inicio');
    Route::resource('/periodo', App\Http\Controllers\PeriodoController::class);

    Route::get('/curso/inicio', [App\Http\Controllers\CursoController::class, 'inicio'])->name('curso.inicio');
    Route::resource('/curso', App\Http\Controllers\CursoController::class);

    Route::get('/gradoSeccion/inicio', [App\Http\Controllers\GradoController::class, 'inicio'])->name('gradoSeccion.inicio');
    Route::resource('/gradoSeccion', App\Http\Controllers\GradoController::class);

    Route::get('/alumno/inicio', [App\Http\Controllers\AlumnoController::class, 'inicio'])->name('alumno.inicio');
    Route::resource('/alumno', App\Http\Controllers\AlumnoController::class);

    Route::get('/roles/inicio', [App\Http\Controllers\RolController::class, 'inicio'])->name('roles.inicio');
    Route::resource('/roles', App\Http\Controllers\RolController::class);

    Route::get('/institucion/inicio', [App\Http\Controllers\InstitucionController::class, 'inicio'])->name('institucion.inicio');
    Route::resource('/institucion', App\Http\Controllers\InstitucionController::class);

    Route::get('/asignarCurso/inicio', [App\Http\Controllers\AsignarCursoController::class, 'inicio'])->name('asignarCurso.inicio');

    Route::resource('/asignarCurso', App\Http\Controllers\AsignarCursoController::class);

});
