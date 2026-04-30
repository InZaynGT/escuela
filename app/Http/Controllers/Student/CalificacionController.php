<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Periodo;
use App\Models\Tarea;
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
                'periodos'    => collect(),
                'inscripcion' => null,
            ]);
        }

        $periodos = Periodo::where('anio', date('Y'))->orderBy('id')->get();
        $materias = $inscripcion->gradoSeccion->materias()->get();

        $resumen = $materias->map(function ($materia) use ($estudiante, $periodos) {
            $tareaIds = Tarea::where('id_materia', $materia->id)->pluck('id');

            $calificaciones = TareaEstudiante::where('id_estudiante', $estudiante->id)
                ->whereIn('id_tarea', $tareaIds)
                ->where('calificado', 1)
                ->get()
                ->keyBy('id_tarea');

            $porPeriodo = [];
            foreach ($periodos as $periodo) {
                $tareasPeriodo = Tarea::where('id_materia', $materia->id)
                    ->where('id_periodo', $periodo->id)
                    ->get();

                $max       = $tareasPeriodo->sum('ponderacion');
                $ganado    = $tareasPeriodo->sum(fn($t) => $calificaciones[$t->id]->calificacion ?? 0);
                $tieneNota = $tareasPeriodo->contains(fn($t) => isset($calificaciones[$t->id]));

                $porPeriodo[$periodo->id] = [
                    'max'    => $max,
                    'ganado' => $tieneNota ? round($ganado, 1) : null,
                ];
            }

            $tieneAlguna = collect($porPeriodo)->contains(fn($p) => $p['ganado'] !== null);
            $total = $tieneAlguna
                ? round(collect($porPeriodo)->sum(fn($p) => ($p['ganado'] ?? 0) / 4), 1)
                : null;

            return [
                'materia'    => $materia,
                'porPeriodo' => $porPeriodo,
                'total'      => $total,
            ];
        });

        return view('student.calificaciones.index', compact('resumen', 'inscripcion', 'periodos'));
    }
}
