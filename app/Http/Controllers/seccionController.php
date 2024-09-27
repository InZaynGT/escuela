<?php

namespace App\Http\Controllers;

use App\Models\seccionModel;
use Illuminate\Http\Request;

class seccionController extends Controller
{
    // Mostrar la lista de materias
    public function index()
    {
        // Obtener todas las materias
        $secciones = seccionModel::all();
        return view('grados.secciones', compact('secciones'));
    }

    // Guardar una nueva materia
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        seccionModel::create([
            'nombre' => $request->input('nombre'),
        ]);

        return redirect()->route('grados.secciones')->with('success', 'Sección agregada exitosamente.');
    }
    // Actualizar una materia existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $grado = seccionModel::findOrFail($id);
        $grado->update([
            'nombre' => $request->input('nombre'),
        ]);

        return redirect()->route('grados.secciones')->with('success', 'Sección actualizada exitosamente.');
    }
}
