<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\GradoSeccion;
use App\Models\Inscripcion;
use App\Models\Materia;
use App\Models\Periodo;
use App\Models\Profesor;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $stats = [
            'estudiantes' => Estudiante::count(),
            'profesores'  => Profesor::count(),
            'materias'    => Materia::count(),
            'inscritos'   => Inscripcion::where('estado', 'activo')
                                ->where('anio', date('Y'))
                                ->count(),
            'grado_secciones' => GradoSeccion::count(),
            'periodo_activo'  => Periodo::where('fecha_inicio', '<=', now())
                                    ->where('fecha_fin', '>=', now())
                                    ->first(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
