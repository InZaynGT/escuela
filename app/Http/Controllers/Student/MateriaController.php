<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Periodo;
use App\Models\Tarea;
use App\Models\TareaEstudiante;
use Illuminate\Http\Request;

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
            return view('student.materias.index', ['materias' => collect(), 'inscripcion' => null]);
        }

        $materias = $inscripcion->gradoSeccion->materias()->get();

        return view('student.materias.index', compact('materias', 'inscripcion'));
    }

    public function show(Request $request, $idMateria)
    {
        $estudiante  = auth()->user()->estudiante;
        $inscripcion = $estudiante->inscripcionActiva()->first();
        abort_if(!$inscripcion, 404);

        $materia = $inscripcion->gradoSeccion->materias()->findOrFail($idMateria);

        // Periodos del año actual
        $periodos = Periodo::where('anio', date('Y'))
            ->orderBy('id')->get();

        $idPeriodo = $request->get('id_periodo');

        if (!$idPeriodo) {
            // Vista de unidades: resumen por periodo
            $tareaIds = Tarea::where('id_materia', $idMateria)->pluck('id');

            $calificaciones = TareaEstudiante::where('id_estudiante', $estudiante->id)
                ->whereIn('id_tarea', $tareaIds)
                ->where('calificado', 1)
                ->get()
                ->keyBy('id_tarea');

            // Por cada periodo: max posible y ganado
            $resumenPorPeriodo = [];
            foreach ($periodos as $periodo) {
                $tareasPeriodo = Tarea::where('id_materia', $idMateria)
                    ->where('id_periodo', $periodo->id)
                    ->get();

                $maxPts    = $tareasPeriodo->sum('ponderacion');
                $ganadoPts = $tareasPeriodo->sum(fn($t) => $calificaciones[$t->id]->calificacion ?? 0);
                $tieneNota = $tareasPeriodo->contains(fn($t) => isset($calificaciones[$t->id]));

                $resumenPorPeriodo[$periodo->id] = [
                    'cantidad' => $tareasPeriodo->count(),
                    'max'      => $maxPts,
                    'ganado'   => $tieneNota ? round($ganadoPts, 1) : null,
                ];
            }

            return view('student.materias.show', compact(
                'materia', 'periodos', 'resumenPorPeriodo'
            ));
        }

        // Vista de tareas de un periodo
        $periodo = Periodo::findOrFail($idPeriodo);

        $tareas = Tarea::where('id_materia', $idMateria)
            ->where('id_periodo', $idPeriodo)
            ->orderBy('created_at')
            ->get();

        $calificaciones = TareaEstudiante::where('id_estudiante', $estudiante->id)
            ->whereIn('id_tarea', $tareas->pluck('id'))
            ->where('calificado', 1)
            ->get()
            ->keyBy('id_tarea');

        return view('student.materias.show', compact(
            'materia', 'periodos', 'periodo', 'tareas', 'calificaciones'
        ));
    }
}
