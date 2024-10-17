<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\profesoresModel;

class profesoresController extends Controller
{
    public function index()
    {
        // Obtener todos los profesores
        $profesores = DB::table('profesores')
            ->join('users', 'profesores.ID_USUARIO', '=', 'users.id')
            ->select(
                'profesores.id',
                DB::raw("CONCAT(profesores.apellidos, ' ', profesores.nombre) as nombre_completo"),
                'profesores.telefono',
                'users.name as usuario',
                'profesores.ID_USUARIO'
            )
            ->orderBy('profesores.apellidos')
            ->get();

        // Obtener todos los usuarios disponibles
        $usuarios = DB::table('users')->select('id', 'name')->get();

        return view('profesores.index', compact('profesores', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:15',
            'usuario_id' => 'required|exists:users,id',
        ]);

            profesoresModel::create([
                'nombre' => $request->input('nombres'),
                'apellidos' => $request->input('apellidos'),
                'telefono' => $request->input('telefono'),
                'ID_USUARIO' => $request->input('usuario_id'),
            ]);

            return redirect()->route('profesores.index')->with('success', 'Profesor agregado exitosamente.');
    }
}
