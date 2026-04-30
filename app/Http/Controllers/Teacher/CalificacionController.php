<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Tarea;
use App\Models\TareaEstudiante;
use Illuminate\Http\Request;

class CalificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'teacher']);
    }

    public function index($idMateria)
    {
        $profesor = auth()->user()->profesor;

        $materia = $profesor->materias()
            ->with(['gradoSeccion.grado', 'gradoSeccion.seccion'])
            ->where('materias.id', $idMateria)
            ->first();

        if (!$materia) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'No tienes acceso a esta materia.');
        }

        $estudiantes = $materia->gradoSeccion->inscripciones()
            ->where('estado', 'activo')
            ->where('anio', date('Y'))
            ->with('estudiante')
            ->get()
            ->pluck('estudiante')
            ->filter()
            ->sortBy('apellidos')
            ->values();

        if ($estudiantes->isEmpty()) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'No hay estudiantes inscritos en esta materia.');
        }

        $tareas = Tarea::where('id_materia', $idMateria)
            ->orderBy('created_at')
            ->get();

        if ($tareas->isEmpty()) {
            return redirect()->route('teacher.tareas.index', $idMateria)
                ->with('info', 'Primero debes crear tareas para esta materia.');
        }

        // Clave: [id_estudiante][id_tarea]
        $estudianteIds = $estudiantes->pluck('id');
        $tareaIds      = $tareas->pluck('id');

        $calificaciones = TareaEstudiante::whereIn('id_estudiante', $estudianteIds)
            ->whereIn('id_tarea', $tareaIds)
            ->get()
            ->groupBy('id_estudiante')
            ->map(fn($g) => $g->keyBy('id_tarea'));

        return view('teacher.calificaciones.index',
            compact('materia', 'estudiantes', 'tareas', 'calificaciones', 'profesor'));
    }

    public function store(Request $request, $idMateria)
    {
        $profesor = auth()->user()->profesor;

        $materia = $profesor->materias()
            ->where('materias.id', $idMateria)
            ->first();

        if (!$materia) {
            return response()->json(['error' => 'Sin acceso'], 403);
        }

        foreach ($request->input('notas', []) as $estId => $tareas) {
            foreach ($tareas as $tareaId => $datos) {
                $this->guardarNota($estId, $tareaId, $datos);
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => 'Notas guardadas correctamente.']);
        }

        return redirect()->back()->with('success', 'Notas guardadas correctamente.');
    }

    private function guardarNota(int $estId, int $tareaId, array $datos): void
    {
        $entrego      = ($datos['entrego'] ?? '1') === '0' ? 0 : 1;
        $calificacion = $datos['calificacion'] ?? null;

        if ($calificacion === null && $entrego === 1) {
            return;
        }

        TareaEstudiante::updateOrCreate(
            ['id_estudiante' => $estId, 'id_tarea' => $tareaId],
            [
                'calificacion' => $entrego === 0 ? 0 : (float) $calificacion,
                'observaciones' => $datos['observaciones'] ?? null,
                'calificado'   => 1,
                'entrego'      => $entrego,
            ]
        );
    }
}
