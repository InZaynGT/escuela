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

    // Método para mostrar los grados y secciones en cards
    public function estudiantes()
    {
        // Obtener los grados, secciones y el conteo de estudiantes
        $gradoSecciones = DB::table('grado_seccion')
            ->join('grados', 'grado_seccion.grado', '=', 'grados.id')
            ->join('seccion', 'grado_seccion.seccion', '=', 'seccion.id')
            ->leftJoin('estudiantes', 'grado_seccion.id', '=', 'estudiantes.grado') // JOIN para contar estudiantes
            ->select(
                'grados.nombre as grado_nombre',
                'grado_seccion.id',
                DB::raw("CONCAT(grados.nombre, ' ', seccion.nombre) as grado_seccion"),
                DB::raw('COUNT(estudiantes.id) as total_estudiantes') // Contar estudiantes
            )
            ->groupBy('grados.id', 'grado_seccion.id', 'grado_seccion.grado') // Agrupar por grado y sección
            ->get();

        // Retornar la vista de estudiantes (cards)
        return view('estudiantes.estudiantes', compact('gradoSecciones'));
    }


    // Método para mostrar los estudiantes filtrados por grado y sección
    public function listadoEstudiantes($gradoSeccionId)
    {
        // Obtener los estudiantes según el grado_seccion_id
        $estudiantes = DB::table('estudiantes')
            ->where('grado', $gradoSeccionId)
            ->get();

        // Obtener información del grado y sección seleccionado
        $gradoSeccion = DB::table('grado_seccion')
            ->join('grados', 'grado_seccion.grado', '=', 'grados.id')
            ->join('seccion', 'grado_seccion.seccion', '=', 'seccion.id')
            ->select(DB::raw("CONCAT(grados.nombre, ' ', seccion.nombre) as grado_seccion"))
            ->where('grado_seccion.id', $gradoSeccionId)
            ->first();

        // Retornar la vista con los estudiantes filtrados
        return view('estudiantes.listadoEstudiantes', compact('estudiantes', 'gradoSeccion'));
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
