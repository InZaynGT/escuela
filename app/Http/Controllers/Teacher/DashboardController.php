<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
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
        
        $materias = $profesor->materias()
            ->with(['gradoSeccion.grado', 'gradoSeccion.seccion'])
            ->get();
        
        return view('teacher.dashboard', compact('materias'));
    }
}