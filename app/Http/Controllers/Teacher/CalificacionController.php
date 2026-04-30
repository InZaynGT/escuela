<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Periodo;
use App\Models\Tarea;
use App\Models\TareaEstudiante;
use Illuminate\Http\Request;

class CalificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'teacher']);
    }

    public function index(Request $request, $idMateria)
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

        $periodos = Periodo::orderBy('id')->get();
        $idPeriodo = $request->get('id_periodo');

        // Sin Unidad seleccionado: mostrar selector
        if (!$idPeriodo) {
            return view('teacher.calificaciones.index', compact(
                'materia', 'periodos', 'profesor'
            ));
        }

        $periodo = Periodo::findOrFail($idPeriodo);

        $estudiantes = $materia->gradoSeccion->inscripciones()
            ->where('estado', 'activo')
            ->where('anio', date('Y'))
            ->with('estudiante')
            ->get()
            ->pluck('estudiante')
            ->filter()
            ->sortBy('apellidos')
            ->values();

        $tareas = Tarea::where('id_materia', $idMateria)
            ->where('id_periodo', $idPeriodo)
            ->orderBy('created_at')
            ->get();

        if ($tareas->isEmpty()) {
            return redirect()
                ->route('teacher.tareas.index', $idMateria)
                ->with('info', "No hay tareas para {$periodo->nombre}. Créalas primero.");
        }

        $estudianteIds = $estudiantes->pluck('id');
        $tareaIds      = $tareas->pluck('id');

        $calificaciones = TareaEstudiante::whereIn('id_estudiante', $estudianteIds)
            ->whereIn('id_tarea', $tareaIds)
            ->get()
            ->groupBy('id_estudiante')
            ->map(fn($g) => $g->keyBy('id_tarea'));

        return view('teacher.calificaciones.index',
            compact('materia', 'estudiantes', 'tareas', 'calificaciones', 'profesor', 'periodos', 'periodo'));
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

        if ($request->has('id_periodo')) {
            $periodo = \App\Models\Periodo::find($request->id_periodo);
            if ($periodo && $periodo->bloqueado) {
                $msg = "El período \"{$periodo->nombre}\" está bloqueado. No se pueden modificar calificaciones.";
                return $request->ajax()
                    ? response()->json(['error' => $msg], 423)
                    : redirect()->back()->with('error', $msg);
            }
        }

        $estIdsValidos   = $materia->gradoSeccion->inscripciones()
            ->where('estado', 'activo')->where('anio', date('Y'))
            ->pluck('id_estudiante')->toArray();
        $tareaIdsValidos = Tarea::where('id_materia', $idMateria)->pluck('id')->toArray();

        $this->procesarNotas($request->input('notas', []), $estIdsValidos, $tareaIdsValidos);

        if ($request->ajax()) {
            return response()->json(['success' => 'Notas guardadas correctamente.']);
        }

        return redirect()->back()->with('success', 'Notas guardadas correctamente.');
    }

    private function procesarNotas(array $notas, array $estIdsValidos, array $tareaIdsValidos): void
    {
        foreach ($notas as $estId => $tareas) {
            if (!in_array((int) $estId, $estIdsValidos)) {
                continue;
            }
            foreach ($tareas as $tareaId => $datos) {
                if (!in_array((int) $tareaId, $tareaIdsValidos)) {
                    continue;
                }
                $this->guardarNota((int) $estId, (int) $tareaId, $datos);
            }
        }
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
