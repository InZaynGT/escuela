<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grado;
use App\Models\Seccion;
use App\Models\GradoSeccion;
use Illuminate\Http\Request;

class GradoSeccionController extends Controller
{
    public function index()
    {
        $combinaciones = GradoSeccion::with(['grado', 'seccion'])->paginate(50);
        return view('admin.grado-seccion.index', compact('combinaciones'));
    }
    
    public function create()
    {
        $grados = Grado::all();
        $secciones = Seccion::all();
        return view('admin.grado-seccion.create', compact('grados', 'secciones'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'id_grado' => 'required|exists:grados,id',
            'id_seccion' => 'required|exists:secciones,id'
        ]);
        
        // Verificar que no exista duplicado
        $existe = GradoSeccion::where('id_grado', $request->id_grado)
            ->where('id_seccion', $request->id_seccion)
            ->exists();
            
        if ($existe) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Esta combinación ya existe.');
        }
        
        GradoSeccion::create($request->all());
        
        return redirect()->route('admin.grado-seccion.index')
            ->with('success', 'Combinación creada exitosamente.');
    }
    
    public function destroy($id)
    {
        $combinacion = GradoSeccion::findOrFail($id);
        $combinacion->delete();
        
        return redirect()->route('admin.grado-seccion.index')
            ->with('success', 'Combinación eliminada exitosamente.');
    }
}