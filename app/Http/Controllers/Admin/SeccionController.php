<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seccion;
use Illuminate\Http\Request;

class SeccionController extends Controller
{
    public function index()
    {
        $secciones = Seccion::orderBy('id')->paginate(50);
        return view('admin.secciones.index', compact('secciones'));
    }
    
    public function create()
    {
        return view('admin.secciones.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:secciones,nombre'
        ]);
        
        Seccion::create($request->all());
        
        return redirect()->route('admin.secciones.index')
            ->with('success', 'Sección creada exitosamente.');
    }
    
    public function edit(Seccion $seccion)
    {
        return view('admin.secciones.edit', compact('seccion'));
    }
    
    public function update(Request $request, Seccion $seccion)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:secciones,nombre,' . $seccion->id
        ]);
        
        $seccion->update($request->all());
        
        return redirect()->route('admin.secciones.index')
            ->with('success', 'Sección actualizada exitosamente.');
    }
    
    public function destroy(Seccion $seccion)
    {
        $seccion->delete();
        
        return redirect()->route('admin.secciones.index')
            ->with('success', 'Sección eliminada exitosamente.');
    }
}