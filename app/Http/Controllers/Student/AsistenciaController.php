<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Periodo;

class AsistenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'student']);
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $estudiante = auth()->user()->estudiante;

        if (!$estudiante) {
            return redirect()->route('student.dashboard')
                ->with('error', 'No se encontró el perfil de estudiante.');
        }

        $idPeriodo = $request->get('id_periodo');

        $base = Asistencia::where('id_estudiante', $estudiante->id)
            ->when($idPeriodo, fn($q) => $q->where('id_periodo', $idPeriodo));

        $stats = (clone $base)
            ->selectRaw("COUNT(*) as total,
                SUM(estado = 'presente')    as presentes,
                SUM(estado = 'ausente')     as ausentes,
                SUM(estado = 'tardanza')    as tardanzas,
                SUM(estado = 'justificado') as justificados")
            ->first();

        $total        = $stats->total        ?? 0;
        $presentes    = $stats->presentes    ?? 0;
        $ausentes     = $stats->ausentes     ?? 0;
        $tardanzas    = $stats->tardanzas    ?? 0;
        $justificados = $stats->justificados ?? 0;
        $porcentaje   = $total > 0 ? round(($presentes / $total) * 100, 1) : null;

        $asistencias = (clone $base)
            ->with('gradoSeccion.grado', 'gradoSeccion.seccion', 'periodo')
            ->orderByDesc('fecha')
            ->paginate(50)
            ->withQueryString();

        $periodos = Periodo::orderBy('id')->get();

        return view('student.asistencia.index', compact(
            'asistencias', 'total', 'presentes', 'ausentes',
            'tardanzas', 'justificados', 'porcentaje', 'periodos'
        ));
    }
}
