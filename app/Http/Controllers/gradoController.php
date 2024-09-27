<?php

namespace App\Http\Controllers;

use App\Models\gradoModel;
use Illuminate\Http\Request;

class gradoController extends Controller
{
     // Mostrar la lista de materias
     public function index()
     {
         // Obtener todas las materias
         $grados = gradoModel::all();
         return view('grados.index', compact('grados'));
     }
 
     // Guardar una nueva materia
     public function store(Request $request)
     {
         $request->validate([
             'nombre' => 'required|string|max:255',
         ]);
 
         gradoModel::create([
             'nombre' => $request->input('nombre'),
         ]);
 
         return redirect()->route('grados.index')->with('success', 'Grado agregado exitosamente.');
     }
     // Actualizar una materia existente
     public function update(Request $request, $id)
     {
         $request->validate([
             'nombre' => 'required|string|max:255',
         ]);
 
         $grado = gradoModel::findOrFail($id);
         $grado->update([
             'nombre' => $request->input('nombre'),
         ]);
 
         return redirect()->route('grados.index')->with('success', 'Grado actualizado exitosamente.');
     }
}
