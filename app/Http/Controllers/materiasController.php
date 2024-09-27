<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\materiasModel;

class materiasController extends Controller
{
    // Mostrar la lista de materias
    public function index()
    {
        // Obtener todas las materias
        $materias = materiasModel::all();
        return view('materias.index', compact('materias'));
    }

    // Guardar una nueva materia
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        materiasModel::create([
            'nombre' => $request->input('nombre'),
        ]);

        return redirect()->route('materias.index')->with('success', 'Materia agregada exitosamente.');
    }
    // Actualizar una materia existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $materia = materiasModel::findOrFail($id);
        $materia->update([
            'nombre' => $request->input('nombre'),
        ]);

        return redirect()->route('materias.index')->with('success', 'Materia actualizada exitosamente.');
    }
}
