<?php

use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\PersonaController;
use Illuminate\Support\Facades\Route;


Route::POST('provincia', [DepartamentoController::class, 'searchProvincia']);
Route::POST('distrito', [DepartamentoController::class, 'searchDistrito']);
Route::POST('personas', [PersonaController::class,'searchDni']);
// Route::resource('personalAcademico', 'PersonalAcademicoController');
// Route::resource('roles', 'RolController');

