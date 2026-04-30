<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\GradoSeccion;
use App\Models\Periodo;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'teacher']);
    }

    // Lista los grado-secciones asignados al docente (a través de sus materias)
    public function index()
    {
        $profesor = auth()->user()->profesor;

        $gradoSecciones = $profesor->materias()
            ->with('gradoSeccion.grado', 'gradoSeccion.seccion')
            ->get()
            ->pluck('gradoSeccion')
            ->unique('id')
            ->filter()
            ->values();

        return view('teacher.asistencia.index', compact('gradoSecciones'));
    }

    // Muestra el formulario de registro/edición para un grado-sección y fecha
    public function registrar(Request $request, $idGradoSeccion)
    {
        $profesor = auth()->user()->profesor;

        // Verificar que el docente tiene al menos una materia en ese grado-sección
        $tieneAcceso = $profesor->materias()
            ->where('id_grado_seccion', $idGradoSeccion)
            ->exists();

        if (!$tieneAcceso) {
            return redirect()->route('teacher.asistencia.index')
                ->with('error', 'No tienes acceso a ese grado-sección.');
        }

        $gradoSeccion = GradoSeccion::with('grado', 'seccion')->findOrFail($idGradoSeccion);

        // Detect active period first so we can clamp the date to its bounds
        $periodoActivo = Periodo::where('fecha_inicio', '<=', now()->toDateString())
            ->where('fecha_fin', '>=', now()->toDateString())
            ->first();

        $today = now()->toDateString();
        $fecha = $request->get('fecha', $today);

        // Clamp to today if future date was requested
        if ($fecha > $today) {
            $fecha = $today;
        }

        // If there is an active period, clamp date within its bounds
        if ($periodoActivo) {
            if ($fecha < $periodoActivo->fecha_inicio) {
                $fecha = $periodoActivo->fecha_inicio;
            }
            if ($fecha > $periodoActivo->fecha_fin) {
                $fecha = min($today, $periodoActivo->fecha_fin);
            }
        }

        $estudiantes = $gradoSeccion->inscripciones()
            ->where('estado', 'activo')
            ->where('anio', date('Y'))
            ->with('estudiante')
            ->get()
            ->pluck('estudiante')
            ->filter()
            ->sortBy('apellidos')
            ->values();

        $asistenciasHoy = Asistencia::where('id_grado_seccion', $idGradoSeccion)
            ->where('fecha', $fecha)
            ->get()
            ->keyBy('id_estudiante');

        $periodos = Periodo::orderBy('id')->get();

        return view('teacher.asistencia.registrar', compact(
            'gradoSeccion', 'estudiantes', 'fecha',
            'asistenciasHoy', 'periodos', 'periodoActivo'
        ));
    }

    // Guarda o actualiza la asistencia del día para el grado-sección
    public function guardar(Request $request, $idGradoSeccion)
    {
        $profesor = auth()->user()->profesor;

        $tieneAcceso = $profesor->materias()
            ->where('id_grado_seccion', $idGradoSeccion)
            ->exists();

        if (!$tieneAcceso) {
            return redirect()->route('teacher.asistencia.index')
                ->with('error', 'No tienes acceso a ese grado-sección.');
        }

        $today = now()->toDateString();

        $request->validate([
            'fecha'                => ['required', 'date', 'before_or_equal:' . $today],
            'id_periodo'           => 'nullable|exists:periodos,id',
            'asistencias'          => 'required|array',
            'asistencias.*.estado' => 'required|in:presente,ausente,tardanza,justificado',
        ]);

        // If a period is selected, ensure the date falls within it
        if ($request->id_periodo) {
            $periodo = Periodo::find($request->id_periodo);
            if ($periodo && ($request->fecha < $periodo->fecha_inicio || $request->fecha > $periodo->fecha_fin)) {
                return back()->withErrors(['fecha' => 'La fecha no corresponde al ciclo seleccionado.'])->withInput();
            }
        }

        foreach ($request->asistencias as $idEstudiante => $datos) {
            Asistencia::updateOrCreate(
                [
                    'id_estudiante'    => $idEstudiante,
                    'fecha'            => $request->fecha,
                ],
                [
                    'id_grado_seccion' => $idGradoSeccion,
                    'id_periodo'       => $request->id_periodo ?: null,
                    'estado'           => $datos['estado'],
                    'observacion'      => $datos['observacion'] ?? null,
                ]
            );
        }

        return redirect()
            ->route('teacher.asistencia.registrar', [
                'idGradoSeccion' => $idGradoSeccion,
                'fecha'          => $request->fecha,
            ])
            ->with('success', 'Asistencia guardada correctamente.');
    }
}
