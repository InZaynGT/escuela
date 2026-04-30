<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TareaEstudiante;

class MateriaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'student']);
    }

    public function index()
    {
        $estudiante = auth()->user()->estudiante;

        if (!$estudiante) {
            return redirect()->route('student.dashboard')
                ->with('error', 'No se encontró el perfil de estudiante.');
        }

        $inscripcion = $estudiante->inscripcionActiva()->first();

        if (!$inscripcion) {
            return view('student.materias.index', [
                'materias'    => collect(),
                'inscripcion' => null,
            ]);
        }

        $materias = $inscripcion->gradoSeccion
            ->materias()
            ->with(['tareas' => function ($q) {
                $q->orderBy('created_at', 'asc');
            }])
            ->get();

        return view('student.materias.index', compact('materias', 'inscripcion'));
    }

    public function show($idMateria)
    {
        $estudiante = auth()->user()->estudiante;

        $inscripcion = $estudiante->inscripcionActiva()->first();
        abort_if(!$inscripcion, 404);

        $materia = $inscripcion->gradoSeccion
            ->materias()
            ->with('tareas')
            ->findOrFail($idMateria);

        $tareaIds = $materia->tareas->pluck('id');

        $calificaciones = TareaEstudiante::where('id_estudiante', $estudiante->id)
            ->whereIn('id_tarea', $tareaIds)
            ->get()
            ->keyBy('id_tarea');

        return view('student.materias.show', compact('materia', 'calificaciones'));
    }
}
