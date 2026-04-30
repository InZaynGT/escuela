<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

// Ruta de fallback: redirige según el rol del usuario
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isAdmin())   return redirect()->route('admin.dashboard');
    if ($user->isTeacher()) return redirect()->route('teacher.dashboard');
    if ($user->isStudent()) return redirect()->route('student.dashboard');
    abort(403, 'Tu cuenta no tiene un rol asignado. Contacta al administrador.');
})->middleware('auth')->name('dashboard');

// RUTAS DE ADMIN
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('grados', App\Http\Controllers\Admin\GradoController::class);

    Route::get('/secciones', [App\Http\Controllers\Admin\SeccionController::class, 'index'])->name('secciones.index');
    Route::get('/secciones/create', [App\Http\Controllers\Admin\SeccionController::class, 'create'])->name('secciones.create');
    Route::post('/secciones', [App\Http\Controllers\Admin\SeccionController::class, 'store'])->name('secciones.store');
    Route::get('/secciones/{id}/edit', [App\Http\Controllers\Admin\SeccionController::class, 'edit'])->name('secciones.edit');
    Route::put('/secciones/{id}', [App\Http\Controllers\Admin\SeccionController::class, 'update'])->name('secciones.update');
    Route::delete('/secciones/{id}', [App\Http\Controllers\Admin\SeccionController::class, 'destroy'])->name('secciones.destroy');

    Route::get('/grado-seccion', [App\Http\Controllers\Admin\GradoSeccionController::class, 'index'])->name('grado-seccion.index');
    Route::get('/grado-seccion/create', [App\Http\Controllers\Admin\GradoSeccionController::class, 'create'])->name('grado-seccion.create');
    Route::post('/grado-seccion', [App\Http\Controllers\Admin\GradoSeccionController::class, 'store'])->name('grado-seccion.store');
    Route::delete('/grado-seccion/{id}', [App\Http\Controllers\Admin\GradoSeccionController::class, 'destroy'])->name('grado-seccion.destroy');

    Route::get('/materias', [App\Http\Controllers\Admin\MateriaController::class, 'index'])->name('materias.index');
    Route::get('/materias/create', [App\Http\Controllers\Admin\MateriaController::class, 'create'])->name('materias.create');
    Route::post('/materias', [App\Http\Controllers\Admin\MateriaController::class, 'store'])->name('materias.store');
    Route::get('/materias/{id}/edit', [App\Http\Controllers\Admin\MateriaController::class, 'edit'])->name('materias.edit');
    Route::put('/materias/{id}', [App\Http\Controllers\Admin\MateriaController::class, 'update'])->name('materias.update');
    Route::delete('/materias/{id}', [App\Http\Controllers\Admin\MateriaController::class, 'destroy'])->name('materias.destroy');

    Route::get('/profesores', [App\Http\Controllers\Admin\ProfesorController::class, 'index'])->name('profesores.index');
    Route::get('/profesores/create', [App\Http\Controllers\Admin\ProfesorController::class, 'create'])->name('profesores.create');
    Route::post('/profesores', [App\Http\Controllers\Admin\ProfesorController::class, 'store'])->name('profesores.store');
    Route::get('/profesores/{id}/edit', [App\Http\Controllers\Admin\ProfesorController::class, 'edit'])->name('profesores.edit');
    Route::put('/profesores/{id}', [App\Http\Controllers\Admin\ProfesorController::class, 'update'])->name('profesores.update');
    Route::delete('/profesores/{id}', [App\Http\Controllers\Admin\ProfesorController::class, 'destroy'])->name('profesores.destroy');
    Route::get('/profesores/{id}/asignar', [App\Http\Controllers\Admin\ProfesorController::class, 'asignar'])->name('profesores.asignar');
    Route::post('/profesores/{id}/asignar', [App\Http\Controllers\Admin\ProfesorController::class, 'guardarAsignacion'])->name('profesores.asignar.guardar');

    Route::get('/estudiantes', [App\Http\Controllers\Admin\EstudianteController::class, 'index'])->name('estudiantes.index');
    Route::get('/estudiantes/create', [App\Http\Controllers\Admin\EstudianteController::class, 'create'])->name('estudiantes.create');
    Route::post('/estudiantes', [App\Http\Controllers\Admin\EstudianteController::class, 'store'])->name('estudiantes.store');
    Route::get('/estudiantes/{id}/edit', [App\Http\Controllers\Admin\EstudianteController::class, 'edit'])->name('estudiantes.edit');
    Route::put('/estudiantes/{id}', [App\Http\Controllers\Admin\EstudianteController::class, 'update'])->name('estudiantes.update');
    Route::delete('/estudiantes/{id}', [App\Http\Controllers\Admin\EstudianteController::class, 'destroy'])->name('estudiantes.destroy');
    Route::get('/estudiantes/{id}/inscribir', [App\Http\Controllers\Admin\EstudianteController::class, 'inscribir'])->name('estudiantes.inscribir');
    Route::post('/estudiantes/{id}/inscribir', [App\Http\Controllers\Admin\EstudianteController::class, 'guardarInscripcion'])->name('estudiantes.inscribir.guardar');

    Route::get('/periodos', [App\Http\Controllers\Admin\PeriodoController::class, 'index'])->name('periodos.index');
    Route::get('/periodos/create', [App\Http\Controllers\Admin\PeriodoController::class, 'create'])->name('periodos.create');
    Route::post('/periodos', [App\Http\Controllers\Admin\PeriodoController::class, 'store'])->name('periodos.store');
    Route::get('/periodos/{id}/edit', [App\Http\Controllers\Admin\PeriodoController::class, 'edit'])->name('periodos.edit');
    Route::put('/periodos/{id}', [App\Http\Controllers\Admin\PeriodoController::class, 'update'])->name('periodos.update');
    Route::delete('/periodos/{id}', [App\Http\Controllers\Admin\PeriodoController::class, 'destroy'])->name('periodos.destroy');

    // Responsables
    Route::get('/responsables', [App\Http\Controllers\Admin\ResponsableController::class, 'index'])->name('responsables.index');
    Route::get('/responsables/create', [App\Http\Controllers\Admin\ResponsableController::class, 'create'])->name('responsables.create');
    Route::post('/responsables', [App\Http\Controllers\Admin\ResponsableController::class, 'store'])->name('responsables.store');
    Route::get('/responsables/{id}/edit', [App\Http\Controllers\Admin\ResponsableController::class, 'edit'])->name('responsables.edit');
    Route::put('/responsables/{id}', [App\Http\Controllers\Admin\ResponsableController::class, 'update'])->name('responsables.update');
    Route::delete('/responsables/{id}', [App\Http\Controllers\Admin\ResponsableController::class, 'destroy'])->name('responsables.destroy');
    Route::get('/responsables/{id}/asignar', [App\Http\Controllers\Admin\ResponsableController::class, 'asignar'])->name('responsables.asignar');
    Route::post('/responsables/{id}/asignar', [App\Http\Controllers\Admin\ResponsableController::class, 'guardarAsignacion'])->name('responsables.asignar.guardar');

    // Reportes
    Route::get('/reportes/grado-seccion', [App\Http\Controllers\Admin\ReporteController::class, 'gradoSeccion'])->name('reportes.grado-seccion');
    Route::get('/reportes/boletas', [App\Http\Controllers\Admin\ReporteController::class, 'boletas'])->name('reportes.boletas');
    Route::get('/reportes/asistencia', [App\Http\Controllers\Admin\ReporteController::class, 'asistencia'])->name('reportes.asistencia');
});

// RUTAS DE TEACHER
Route::middleware(['auth', 'teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');

    // Tareas
    Route::get('/materias/{idMateria}/tareas', [App\Http\Controllers\Teacher\TareaController::class, 'index'])->name('tareas.index');
    Route::get('/materias/{idMateria}/tareas/create', [App\Http\Controllers\Teacher\TareaController::class, 'create'])->name('tareas.create');
    Route::post('/materias/{idMateria}/tareas', [App\Http\Controllers\Teacher\TareaController::class, 'store'])->name('tareas.store');
    Route::get('/materias/{idMateria}/tareas/{idTarea}/edit', [App\Http\Controllers\Teacher\TareaController::class, 'edit'])->name('tareas.edit');
    Route::put('/materias/{idMateria}/tareas/{idTarea}', [App\Http\Controllers\Teacher\TareaController::class, 'update'])->name('tareas.update');
    Route::delete('/materias/{idMateria}/tareas/{idTarea}', [App\Http\Controllers\Teacher\TareaController::class, 'destroy'])->name('tareas.destroy');

    // Calificaciones
    Route::get('/materias/{idMateria}/calificaciones', [App\Http\Controllers\Teacher\CalificacionController::class, 'index'])->name('calificaciones.index');
    Route::post('/materias/{idMateria}/calificaciones', [App\Http\Controllers\Teacher\CalificacionController::class, 'store'])->name('calificaciones.store');

    // Asistencia
    Route::get('/asistencia', [App\Http\Controllers\Teacher\AsistenciaController::class, 'index'])->name('asistencia.index');
    Route::get('/asistencia/{idMateria}', [App\Http\Controllers\Teacher\AsistenciaController::class, 'registrar'])->name('asistencia.registrar');
    Route::post('/asistencia/{idMateria}', [App\Http\Controllers\Teacher\AsistenciaController::class, 'guardar'])->name('asistencia.guardar');
});

// RUTAS DE STUDENT
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');

    // Materias
    Route::get('/materias', [App\Http\Controllers\Student\MateriaController::class, 'index'])->name('materias.index');
    Route::get('/materias/{idMateria}', [App\Http\Controllers\Student\MateriaController::class, 'show'])->name('materias.show');

    // Calificaciones
    Route::get('/calificaciones', [App\Http\Controllers\Student\CalificacionController::class, 'index'])->name('calificaciones.index');

    // Asistencia
    Route::get('/asistencia', [App\Http\Controllers\Student\AsistenciaController::class, 'index'])->name('asistencia.index');
});
