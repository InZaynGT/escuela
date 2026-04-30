<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('student');
    }
    
    public function index()
    {
        // Obtener el estudiante asociado al usuario actual
        $estudiante = auth()->user()->estudiante;
        
        return view('student.dashboard', compact('estudiante'));
    }
}