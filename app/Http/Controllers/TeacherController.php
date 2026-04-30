<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profesor;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher');
    }
    
    public function index()
    {
        // Obtener el profesor asociado al usuario actual
        $profesor = auth()->user()->profesor;
        
        // Obtener las materias del profesor
        $materias = $profesor ? $profesor->materias()->with('gradoSeccion')->get() : collect();
        
        return view('teacher.dashboard', compact('materias'));
    }
}