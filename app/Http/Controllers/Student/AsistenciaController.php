<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;

class AsistenciaController extends Controller
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

        $stats = Asistencia::where('id_estudiante', $estudiante->id)
            ->selectRaw("COUNT(*) as total,
                SUM(estado = 'presente') as presentes,
                SUM(estado = 'ausente') as ausentes,
                SUM(estado = 'tardanza') as tardanzas,
                SUM(estado = 'justificado') as justificados")
            ->first();

        $total        = $stats->total;
        $presentes    = $stats->presentes;
        $ausentes     = $stats->ausentes;
        $tardanzas    = $stats->tardanzas;
        $justificados = $stats->justificados;
        $porcentaje   = $total > 0 ? round(($presentes / $total) * 100, 1) : null;

        $asistencias = Asistencia::where('id_estudiante', $estudiante->id)
            ->with('materia')
            ->orderByDesc('fecha')
            ->paginate(50);

        return view('student.asistencia.index', compact(
            'asistencias', 'total', 'presentes', 'ausentes',
            'tardanzas', 'justificados', 'porcentaje', 'inscripcion'
        ));
    }
}
