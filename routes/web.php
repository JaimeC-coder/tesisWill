<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to('landing');
    return view('welcome');
});
Route::get('/landing', function () {
    return view('welcome');
});
