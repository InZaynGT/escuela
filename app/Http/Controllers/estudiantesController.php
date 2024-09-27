<?php

namespace App\Http\Controllers;

use App\Models\estudiantesModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class estudiantesController extends Controller
{
    public function index()
    {
        // Obtener todas las materias
        $estudiantes = DB::table('estudiantes')
            ->join('grado_seccion', 'estudiantes.grado', '=', 'grado_seccion.id')
            ->join('grados', 'grado_seccion.grado', '=', 'grados.id')
            ->join('seccion', 'grado_seccion.seccion', '=', 'seccion.id')
            ->select(
                'estudiantes.id',
                'grado_seccion.id',
                'estudiantes.carne',
                DB::raw("CONCAT(estudiantes.apellidos, ' ', estudiantes.nombre) as nombre"),
                'CUI',
                DB::raw("CONCAT(grados.nombre, ' ', seccion.nombre) as grado")
            )
            ->orderBy('estudiantes.apellidos')
            ->get();
            
        $grados = DB::table('grado_seccion')
            ->join('grados', 'grado_seccion.grado', '=', 'grados.id')
            ->join('seccion', 'grado_seccion.seccion', '=', 'seccion.id')
            ->select(
                'grado_seccion.id',
                DB::raw("CONCAT(grados.nombre, ' ', seccion.nombre) as nombre")
            )->get();
        return view('estudiantes.index', compact('estudiantes', 'grados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'carne' => 'required|integer|max:255',
            'CUI' => 'required|string|max:255',
            'grado_id' => 'required|integer|max:255',
        ]);

        estudiantesModel::create([
            'nombre' => $request->input('nombres'),
            'apellidos' => $request->input('apellidos'),
            'carne' => $request->input('carne'),
            'CUI' => $request->input('CUI'),
            'grado' => $request->input('grado_id')
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante agregado exitosamente.');
    }
}
