<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TareaEstudiante;

class CalificacionController extends Controller
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
            return view('student.calificaciones.index', [
                'resumen'     => collect(),
                'inscripcion' => null,
            ]);
        }

        $materias = $inscripcion->gradoSeccion->materias()->with('tareas')->get();

        $resumen = $materias->map(function ($materia) use ($estudiante) {
            $tareas = $materia->tareas;

            if ($tareas->isEmpty()) {
                return [
                    'materia'   => $materia,
                    'tareas'    => collect(),
                    'promedio'  => null,
                    'aprobado'  => null,
                ];
            }

            $calificaciones = TareaEstudiante::where('id_estudiante', $estudiante->id)
                ->whereIn('id_tarea', $tareas->pluck('id'))
                ->where('calificado', 1)
                ->get()
                ->keyBy('id_tarea');

            $tareaDetalle = $tareas->map(function ($tarea) use ($calificaciones) {
                $cal = $calificaciones[$tarea->id] ?? null;
                return [
                    'tarea'        => $tarea,
                    'calificacion' => $cal,
                ];
            });

            $calificadasConNota = $calificaciones->values();
            $promedio = $calificadasConNota->isNotEmpty()
                ? round($calificadasConNota->avg('calificacion'), 2)
                : null;

            return [
                'materia'  => $materia,
                'tareas'   => $tareaDetalle,
                'promedio' => $promedio,
                'aprobado' => $promedio !== null ? ($promedio >= 60) : null,
            ];
        });

        return view('student.calificaciones.index', compact('resumen', 'inscripcion'));
    }
}
