<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\materiasModel;
use Illuminate\Support\Facades\DB;

class materiasController extends Controller
{
    // Mostrar la lista de materias
    public function index()
    {
        // Obtener todas las materias
        $materias = DB::table('materia')
        ->join('grado_seccion', 'materia.ID_GRADO_SECCION', '=', 'grado_seccion.id')
        ->join('grados', 'grado_seccion.grado', '=', 'grados.id')
        ->join('seccion', 'grado_seccion.seccion', '=', 'seccion.id')
        ->select(
            'materia.id',
            'grado_seccion.id',
            'materia.nombre',
            DB::raw("CONCAT(grados.nombre, ' ', seccion.nombre) as grado")
        )
        ->orderBy('grado_seccion.id')
        ->get();
        $grados = DB::table('grado_seccion')
            ->join('grados', 'grado_seccion.grado', '=', 'grados.id')
            ->join('seccion', 'grado_seccion.seccion', '=', 'seccion.id')
            ->select(
                'grado_seccion.id',
                DB::raw("CONCAT(grados.nombre, ' ', seccion.nombre) as nombre")
            )->get();
        return view('materias.index', compact('materias', 'grados'));
    }

    // Guardar una nueva materia
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'grado_id' => 'required|integer',
        ]);

        materiasModel::create([
            'nombre' => $request->input('nombre'),
            'ID_GRADO_SECCION' => $request->input('grado_id'),
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
