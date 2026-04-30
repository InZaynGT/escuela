<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use App\Models\GradoSeccion;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    public function index()
    {
        $materias = Materia::with(['gradoSeccion.grado', 'gradoSeccion.seccion'])
            ->orderBy('nombre')
            ->paginate(50);
        return view('admin.materias.index', compact('materias'));
    }
    
    public function create()
    {
        $combinaciones = GradoSeccion::with(['grado', 'seccion'])->get();
        return view('admin.materias.create', compact('combinaciones'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_grado_seccion' => 'required|exists:grado_seccion,id'
        ]);
        
        // Verificar que no exista duplicado en el mismo grado-sección
        $existe = Materia::where('nombre', $request->nombre)
            ->where('id_grado_seccion', $request->id_grado_seccion)
            ->exists();
            
        if ($existe) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Esta materia ya existe para este grado-sección.');
        }
        
        Materia::create($request->all());
        
        return redirect()->route('admin.materias.index')
            ->with('success', 'Materia creada exitosamente.');
    }
    
    public function edit($id)
    {
        $materia = Materia::findOrFail($id);
        $combinaciones = GradoSeccion::with(['grado', 'seccion'])->get();
        return view('admin.materias.edit', compact('materia', 'combinaciones'));
    }
    
    public function update(Request $request, $id)
    {
        $materia = Materia::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_grado_seccion' => 'required|exists:grado_seccion,id'
        ]);
        
        // Verificar duplicado excluyendo la materia actual
        $existe = Materia::where('nombre', $request->nombre)
            ->where('id_grado_seccion', $request->id_grado_seccion)
            ->where('id', '!=', $id)
            ->exists();
            
        if ($existe) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Esta materia ya existe para este grado-sección.');
        }
        
        $materia->update($request->all());
        
        return redirect()->route('admin.materias.index')
            ->with('success', 'Materia actualizada exitosamente.');
    }
    
    public function destroy($id)
    {
        $materia = Materia::findOrFail($id);
        $materia->delete();
        
        return redirect()->route('admin.materias.index')
            ->with('success', 'Materia eliminada exitosamente.');
    }
}