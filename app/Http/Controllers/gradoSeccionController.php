<?php

namespace App\Http\Controllers;

use App\Models\gradoSeccionModel;
use Illuminate\Support\Facades\DB;
use App\Models\gradoModel;
use App\Models\seccionModel;
use Illuminate\Http\Request;

class gradoSeccionController extends Controller
{
    public function index()
    {
        // Obtener todas las materias
        $gradoSecciones = DB::table('grado_seccion')
        ->join('grados', 'grado_seccion.grado', '=', 'grados.id')
        ->join('seccion', 'grado_seccion.seccion', '=', 'seccion.id')
        ->select(
            'grado_seccion.id', 
            DB::raw("CONCAT(grados.nombre, ' ', seccion.nombre) as grado_seccion")
        )
        ->get();
        $grados = gradoModel::all();
        $seccion = seccionModel::all();

        return view('grados.gradoSeccion', compact('gradoSecciones', 'grados', 'seccion'));
    }

    // Guardar una nueva materia
    public function store(Request $request)
    {
        $request->validate([
            'grado_id' => 'required|integer|max:255',
            'seccion_id' => 'required|integer|max:255'
        ]);

        gradoSeccionModel::create([
            'grado' => $request->input('grado_id'),
            'seccion' => $request->input('seccion_id')
        ]);

        return redirect()->route('asocia.index')->with('success', 'Grado agregado exitosamente.');
    }
}
