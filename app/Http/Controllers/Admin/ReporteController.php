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
        $gradoSecciones = GradoSeccion::with('grado', 'seccion')->get();

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
        $estudiantes = Estudiante::orderBy('apellidos')->get();
        $seleccionado = null;
        $materias     = collect();
        $inscripcion  = null;

        $idEstudiante = $request->get('id_estudiante');
        $anio         = $request->get('anio', date('Y'));

        if ($idEstudiante) {
            $seleccionado = Estudiante::with([
                'inscripciones' => fn($q) => $q->where('anio', $anio)->where('estado', 'activo')
                    ->with('gradoSeccion.grado', 'gradoSeccion.seccion', 'gradoSeccion.materias.tareas'),
            ])->find($idEstudiante);

            if ($seleccionado) {
                $inscripcion = $seleccionado->inscripciones->first();

                if ($inscripcion) {
                    $materias = $inscripcion->gradoSeccion->materias->map(function ($materia) use ($seleccionado) {
                        $tareaIds       = $materia->tareas->pluck('id');
                        $calificaciones = TareaEstudiante::where('id_estudiante', $seleccionado->id)
                            ->whereIn('id_tarea', $tareaIds)
                            ->where('calificado', 1)
                            ->get();

                        $promedio = $calificaciones->isNotEmpty()
                            ? round($calificaciones->avg('calificacion'), 2)
                            : null;

                        return [
                            'materia'       => $materia,
                            'calificaciones' => $calificaciones->keyBy('id_tarea'),
                            'promedio'      => $promedio,
                            'aprobado'      => $promedio !== null ? ($promedio >= 60) : null,
                        ];
                    });
                }
            }
        }

        return view('admin.reportes.boleta', compact(
            'estudiantes', 'seleccionado', 'materias', 'inscripcion', 'anio', 'idEstudiante'
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

            $resumen = $estudiantes->map(function ($est) {
                $asistencias  = Asistencia::where('id_estudiante', $est->id)->get();
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
