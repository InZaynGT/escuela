<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Materia;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'teacher']);
    }

    public function index()
    {
        $profesor = auth()->user()->profesor;
        $materias = $profesor->materias()
            ->with('gradoSeccion.grado', 'gradoSeccion.seccion')
            ->get();

        return view('teacher.asistencia.index', compact('materias'));
    }

    public function registrar(Request $request, $idMateria)
    {
        $profesor = auth()->user()->profesor;

        $materia = $profesor->materias()
            ->with('gradoSeccion.grado', 'gradoSeccion.seccion')
            ->where('materias.id', $idMateria)
            ->first();

        if (!$materia) {
            return redirect()->route('teacher.asistencia.index')
                ->with('error', 'No tienes acceso a esa materia.');
        }

        $fecha = $request->get('fecha', now()->toDateString());

        $estudiantes = $materia->gradoSeccion->inscripciones()
            ->where('estado', 'activo')
            ->where('anio', date('Y'))
            ->with('estudiante')
            ->get()
            ->pluck('estudiante')
            ->filter();

        $asistenciasHoy = Asistencia::where('id_materia', $idMateria)
            ->where('fecha', $fecha)
            ->get()
            ->keyBy('id_estudiante');

        return view('teacher.asistencia.registrar', compact(
            'materia', 'estudiantes', 'fecha', 'asistenciasHoy'
        ));
    }

    public function guardar(Request $request, $idMateria)
    {
        $profesor = auth()->user()->profesor;

        $materia = $profesor->materias()
            ->where('materias.id', $idMateria)
            ->first();

        if (!$materia) {
            return redirect()->route('teacher.asistencia.index')
                ->with('error', 'No tienes acceso a esa materia.');
        }

        $request->validate([
            'fecha'          => 'required|date',
            'asistencias'    => 'required|array',
            'asistencias.*.estado' => 'required|in:presente,ausente,tardanza,justificado',
        ]);

        foreach ($request->asistencias as $idEstudiante => $datos) {
            Asistencia::updateOrCreate(
                [
                    'id_estudiante' => $idEstudiante,
                    'id_materia'    => $idMateria,
                    'fecha'         => $request->fecha,
                ],
                [
                    'estado'      => $datos['estado'],
                    'observacion' => $datos['observacion'] ?? null,
                ]
            );
        }

        return redirect()
            ->route('teacher.asistencia.registrar', ['idMateria' => $idMateria, 'fecha' => $request->fecha])
            ->with('success', 'Asistencia guardada correctamente.');
    }
}
