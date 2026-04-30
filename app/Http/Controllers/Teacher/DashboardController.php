<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TareaEstudiante;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher');
    }

    public function index()
    {
        $profesor = auth()->user()->profesor;

        if (!$profesor) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'No se encontró tu perfil de docente. Contacta al administrador.');
        }

        $periodoActual = $this->resolverPeriodoActual();

        $materias = $profesor->materias()
            ->with([
                'gradoSeccion.grado',
                'gradoSeccion.seccion',
                'gradoSeccion.inscripciones' => fn($q) => $q->where('estado', 'activo')->where('anio', date('Y')),
                'tareas' => fn($q) => $periodoActual
                    ? $q->where('id_periodo', $periodoActual->id)
                    : $q->whereRaw('0'),
            ])
            ->get();

        foreach ($materias as $materia) {
            $estCount   = $materia->gradoSeccion->inscripciones->count();
            $tareaCount = $materia->tareas->count();

            $calificadoCount = 0;
            if ($tareaCount > 0) {
                $calificadoCount = TareaEstudiante::whereIn('id_tarea', $materia->tareas->pluck('id'))
                    ->where('calificado', 1)
                    ->count();
            }

            $expected   = $estCount * $tareaCount;
            $pendientes = max(0, $expected - $calificadoCount);
            $porcentaje = $expected > 0 ? (int) round($calificadoCount / $expected * 100) : 100;

            $materia->setAttribute('est_count',        $estCount);
            $materia->setAttribute('tarea_count',      $tareaCount);
            $materia->setAttribute('calificado_count', $calificadoCount);
            $materia->setAttribute('pendientes',       $pendientes);
            $materia->setAttribute('porcentaje',       $porcentaje);
        }

        return view('teacher.dashboard', compact('materias', 'periodoActual'));
    }

    private function resolverPeriodoActual(): ?\App\Models\Periodo
    {
        $hoy = now()->toDateString();

        // Período cuyas fechas encierran hoy
        $periodo = \App\Models\Periodo::where('anio', date('Y'))
            ->where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->first();

        // Fallback: el período más reciente del año actual que ya haya comenzado
        if (!$periodo) {
            $periodo = \App\Models\Periodo::where('anio', date('Y'))
                ->where('fecha_inicio', '<=', $hoy)
                ->orderByDesc('fecha_inicio')
                ->first();
        }

        // Último fallback: cualquier período del año, el de mayor id
        if (!$periodo) {
            $periodo = \App\Models\Periodo::where('anio', date('Y'))
                ->orderByDesc('id')
                ->first();
        }

        return $periodo;
    }
}
