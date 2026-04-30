<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Estudiante;
use App\Models\GradoSeccion;
use App\Models\TareaEstudiante;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    // ── Listado por grado-sección ────────────────────────────────
    public function gradoSeccion(Request $request)
    {
        $gradoSecciones = GradoSeccion::with('grado', 'seccion')->get()->sortBy(fn($gs) => $gs->grado->id);

        $idGradoSeccion = $request->get('id_grado_seccion');
        $anio           = $request->get('anio', date('Y'));
        $estudiantes    = collect();
        $seleccionado   = null;

        if ($idGradoSeccion) {
            $seleccionado = GradoSeccion::with('grado', 'seccion')->find($idGradoSeccion);

            $estudiantes = Estudiante::whereHas('inscripciones', function ($q) use ($idGradoSeccion, $anio) {
                $q->where('id_grado_seccion', $idGradoSeccion)
                  ->where('anio', $anio)
                  ->where('estado', 'activo');
            })->orderBy('apellidos')->get();
        }

        return view('admin.reportes.grado-seccion', compact(
            'gradoSecciones', 'estudiantes', 'seleccionado', 'anio', 'idGradoSeccion'
        ));
    }

    // ── Boleta de notas ──────────────────────────────────────────
    public function boletas(Request $request)
    {
        $gradoSecciones = GradoSeccion::with('grado', 'seccion')
            ->get()->sortBy(fn($gs) => $gs->grado->id);

        $seleccionado = null;
        $materias     = collect();
        $inscripcion  = null;

        $idEstudiante = $request->get('id_estudiante');
        $anio         = $request->get('anio', date('Y'));

        // All periods for the selected year, shown even if no tasks exist yet
        $periodos = \App\Models\Periodo::where('anio', $anio)
            ->orderBy('id')->get();

        if ($idEstudiante) {
            $seleccionado = Estudiante::with([
                'inscripciones' => fn($q) => $q->where('anio', $anio)->where('estado', 'activo')
                    ->with('gradoSeccion.grado', 'gradoSeccion.seccion', 'gradoSeccion.materias.tareas'),
            ])->find($idEstudiante);

            if ($seleccionado) {
                $inscripcion = $seleccionado->inscripciones->first();

                if ($inscripcion) {
                    $materias = $this->calcularMaterias($seleccionado, $inscripcion, $periodos);
                }
            }
        }

        return view('admin.reportes.boleta', compact(
            'gradoSecciones', 'seleccionado', 'materias', 'inscripcion', 'anio', 'idEstudiante', 'periodos'
        ));
    }

    // ── Vista de impresión: boleta individual ────────────────────
    public function boletaPrint(Request $request, $idEstudiante)
    {
        $anio     = $request->get('anio', date('Y'));
        $periodos = \App\Models\Periodo::where('anio', $anio)->orderBy('id')->get();

        $seleccionado = Estudiante::with([
            'inscripciones' => fn($q) => $q->where('anio', $anio)->where('estado', 'activo')
                ->with('gradoSeccion.grado', 'gradoSeccion.seccion', 'gradoSeccion.materias.tareas'),
        ])->findOrFail($idEstudiante);

        $inscripcion = $seleccionado->inscripciones->first();
        abort_if(!$inscripcion, 404, 'Sin inscripción activa para este año.');

        $materias = $this->calcularMaterias($seleccionado, $inscripcion, $periodos);

        return view('admin.reportes.boleta-print', compact(
            'seleccionado', 'inscripcion', 'materias', 'periodos', 'anio'
        ));
    }

    // ── Vista de impresión: todas las boletas de un grado-sección ─
    public function boletasPrintAll(Request $request)
    {
        $request->validate([
            'id_grado_seccion' => 'required|exists:grado_seccion,id',
            'anio'             => 'required|integer',
        ]);

        $anio     = $request->anio;
        $gs       = GradoSeccion::with('grado', 'seccion')->findOrFail($request->id_grado_seccion);
        $periodos = \App\Models\Periodo::where('anio', $anio)->orderBy('id')->get();

        $estudiantes = Estudiante::whereHas('inscripciones', function ($q) use ($request, $anio) {
            $q->where('id_grado_seccion', $request->id_grado_seccion)
              ->where('anio', $anio)
              ->where('estado', 'activo');
        })->with([
            'inscripciones' => fn($q) => $q->where('anio', $anio)->where('estado', 'activo')
                ->with('gradoSeccion.grado', 'gradoSeccion.seccion', 'gradoSeccion.materias.tareas'),
        ])->orderBy('apellidos')->get();

        abort_if($estudiantes->isEmpty(), 404, 'No hay estudiantes activos en este grado-sección.');

        $boletas = $estudiantes->map(function ($est) use ($periodos) {
            $inscripcion = $est->inscripciones->first();
            return [
                'estudiante'  => $est,
                'inscripcion' => $inscripcion,
                'materias'    => $inscripcion ? $this->calcularMaterias($est, $inscripcion, $periodos) : collect(),
            ];
        })->filter(fn($b) => $b['inscripcion'] !== null)->values();

        return view('admin.reportes.boletas-print-all', compact('boletas', 'periodos', 'anio', 'gs'));
    }

    // ── Helper compartido: calcular materias con notas ───────────
    private function calcularMaterias($estudiante, $inscripcion, $periodos)
    {
        return $inscripcion->gradoSeccion->materias->map(function ($materia) use ($estudiante, $periodos) {
            $tareaIds = $materia->tareas->pluck('id');

            $calificaciones = $tareaIds->isNotEmpty()
                ? TareaEstudiante::where('id_estudiante', $estudiante->id)
                    ->whereIn('id_tarea', $tareaIds)
                    ->where('calificado', 1)
                    ->get()
                    ->keyBy('id_tarea')
                : collect();

            $notasPorPeriodo = [];
            foreach ($periodos as $periodo) {
                $tareasUnidad = $materia->tareas->where('id_periodo', $periodo->id);
                if ($tareasUnidad->isEmpty()) {
                    $notasPorPeriodo[$periodo->id] = null;
                    continue;
                }
                $tieneNota = false;
                $suma = 0;
                foreach ($tareasUnidad as $tarea) {
                    $cal = $calificaciones[$tarea->id] ?? null;
                    if ($cal) { $tieneNota = true; $suma += $cal->calificacion; }
                }
                $notasPorPeriodo[$periodo->id] = $tieneNota ? round($suma, 2) : null;
            }

            $tieneAlguna = count(array_filter($notasPorPeriodo, fn($n) => $n !== null)) > 0;
            $notaFinal = $tieneAlguna
                ? round(array_sum(array_map(fn($n) => ($n ?? 0) / 4, $notasPorPeriodo)), 2)
                : null;

            return [
                'materia'         => $materia,
                'notasPorPeriodo' => $notasPorPeriodo,
                'notaFinal'       => $notaFinal,
                'aprobado'        => $notaFinal !== null ? ($notaFinal >= 60) : null,
            ];
        });
    }

    // ── Vista de impresión: asistencia por grado-sección ────────
    public function asistenciaPrint(Request $request)
    {
        $request->validate([
            'id_grado_seccion' => 'required|exists:grado_seccion,id',
            'anio'             => 'required|integer',
        ]);

        $idGradoSeccion = $request->id_grado_seccion;
        $anio           = $request->anio;

        $seleccionado = GradoSeccion::with('grado', 'seccion')->findOrFail($idGradoSeccion);

        $estudiantes = Estudiante::whereHas('inscripciones', function ($q) use ($idGradoSeccion, $anio) {
            $q->where('id_grado_seccion', $idGradoSeccion)
              ->where('anio', $anio)
              ->where('estado', 'activo');
        })->orderBy('apellidos')->get();

        abort_if($estudiantes->isEmpty(), 404, 'No hay estudiantes activos en este grado-sección.');

        $resumen = $estudiantes->map(function ($est) use ($idGradoSeccion) {
            $asistencias = Asistencia::where('id_estudiante', $est->id)
                ->where('id_grado_seccion', $idGradoSeccion)
                ->get();
            $total       = $asistencias->count();
            $presentes   = $asistencias->where('estado', 'presente')->count();
            $ausentes    = $asistencias->where('estado', 'ausente')->count();
            $tardanzas   = $asistencias->where('estado', 'tardanza')->count();
            $porcentaje  = $total > 0 ? round(($presentes / $total) * 100, 1) : null;

            return compact('est', 'total', 'presentes', 'ausentes', 'tardanzas', 'porcentaje');
        });

        return view('admin.reportes.asistencia-print', compact(
            'seleccionado', 'resumen', 'anio'
        ));
    }

    // ── Reporte de asistencia ────────────────────────────────────
    public function asistencia(Request $request)
    {
        $gradoSecciones = GradoSeccion::with('grado', 'seccion')->get();
        $idGradoSeccion = $request->get('id_grado_seccion');
        $anio           = $request->get('anio', date('Y'));
        $resumen        = collect();
        $seleccionado   = null;

        if ($idGradoSeccion) {
            $seleccionado = GradoSeccion::with('grado', 'seccion')->find($idGradoSeccion);

            $estudiantes = Estudiante::whereHas('inscripciones', function ($q) use ($idGradoSeccion, $anio) {
                $q->where('id_grado_seccion', $idGradoSeccion)
                  ->where('anio', $anio)
                  ->where('estado', 'activo');
            })->orderBy('apellidos')->get();

            $resumen = $estudiantes->map(function ($est) use ($idGradoSeccion) {
                $asistencias  = Asistencia::where('id_estudiante', $est->id)
                    ->where('id_grado_seccion', $idGradoSeccion)
                    ->get();
                $total        = $asistencias->count();
                $presentes    = $asistencias->where('estado', 'presente')->count();
                $ausentes     = $asistencias->where('estado', 'ausente')->count();
                $tardanzas    = $asistencias->where('estado', 'tardanza')->count();
                $porcentaje   = $total > 0 ? round(($presentes / $total) * 100, 1) : null;

                return compact('est', 'total', 'presentes', 'ausentes', 'tardanzas', 'porcentaje');
            });
        }

        return view('admin.reportes.asistencia', compact(
            'gradoSecciones', 'resumen', 'seleccionado', 'anio', 'idGradoSeccion'
        ));
    }
}
