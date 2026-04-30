<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periodo;
use Illuminate\Http\Request;

class PeriodoController extends Controller
{
    public function index()
    {
        $periodos = Periodo::orderBy('anio', 'desc')->orderBy('id', 'desc')->paginate(50);
        return view('admin.periodos.index', compact('periodos'));
    }
    
    public function create()
    {
        return view('admin.periodos.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'anio' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio'
        ]);
        
        Periodo::create($request->all());
        
        return redirect()->route('admin.periodos.index')
            ->with('success', 'Periodo creado exitosamente.');
    }
    
    public function edit($id)
    {
        $periodo = Periodo::findOrFail($id);
        return view('admin.periodos.edit', compact('periodo'));
    }
    
    public function update(Request $request, $id)
    {
        $periodo = Periodo::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'anio' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio'
        ]);
        
        $periodo->update($request->all());
        
        return redirect()->route('admin.periodos.index')
            ->with('success', 'Periodo actualizado exitosamente.');
    }
    
    public function destroy($id)
    {
        $periodo = Periodo::findOrFail($id);
        $periodo->delete();
        
        return redirect()->route('admin.periodos.index')
            ->with('success', 'Periodo eliminado exitosamente.');
    }
}