<?php

use App\Http\Controllers\estudiantesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\materiasController;
use App\Http\Controllers\gradoController;
use App\Http\Controllers\gradoSeccionController;
use App\Http\Controllers\seccionController;
use App\Http\Controllers\profesoresController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//MATERIAS
Route::get('/materias', [materiasController::class, 'index'])->name('materias.index');
Route::post('/materias', [materiasController::class, 'store'])->name('materias.store');
Route::put('/materias/{id}', [materiasController::class, 'update'])->name('materias.update');

//GRADOS
Route::get('/grados', [gradoController::class, 'index'])->name('grados.index');
Route::post('/grados', [gradoController::class, 'store'])->name('grados.store');
Route::put('/grados/{id}', [gradoController::class, 'update'])->name('grados.update');

//SECCIONES
Route::get('/secciones', [seccionController::class, 'index'])->name('grados.secciones');
Route::post('/secciones', [seccionController::class, 'store'])->name('secciones.store');
Route::put('/secciones/{id}', [seccionController::class, 'update'])->name('secciones.update');

//GRADO Y SECCIÓN JUNTOS

Route::get('/asocia', [gradoSeccionController::class, 'index'])->name('asocia.index');
Route::post('/asocia', [gradoSeccionController::class, 'store'])->name('asocia.store');
Route::put('/asocia/{id}', [gradoController::class, 'update'])->name('asocia.update');

//CREACIÓN DE ESTUDIANTES

Route::get('/estudiantes', [estudiantesController::class, 'index'])->name('estudiantes.index');
Route::post('/estudiantes', [estudiantesController::class, 'store'])->name('estudiantes.store');
Route::put('/estudiantes/{id}', [estudiantesController::class, 'update'])->name('estudiantes.update');

//PROFESORES
Route::get('/profesores', [ProfesoresController::class, 'index'])->name('profesores.index');
Route::post('/profesores', [ProfesoresController::class, 'store'])->name('profesores.store');
Route::put('/profesores/{id}', [ProfesoresController::class, 'update'])->name('profesores.update');