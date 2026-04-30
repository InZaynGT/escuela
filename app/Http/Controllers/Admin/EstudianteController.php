<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\User;
use App\Models\GradoSeccion;
use App\Models\Inscripcion;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    public function index()
    {
        $estudiantes = Estudiante::with('inscripciones.gradoSeccion.grado', 'inscripciones.gradoSeccion.seccion')
            ->orderBy('apellidos')
            ->paginate(50);
        return view('admin.estudiantes.index', compact('estudiantes'));
    }

    public function create()
    {
        return view('admin.estudiantes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'cui' => 'nullable|string|max:20|unique:estudiantes,cui',
            'telefono' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'email' => 'nullable|email|unique:users,email'
        ]);

        // Crear estudiante
        $estudiante = Estudiante::create($request->only([
            'nombre',
            'apellidos',
            'cui',
            'telefono',
            'fecha_nacimiento'
        ]));

        // Si se proporcionó email, crear usuario
        if ($request->filled('email')) {
            $user = User::create([
                'name' => $request->nombre . ' ' . $request->apellidos,
                'email' => $request->email,
                'password' => bcrypt('estudiante123'), // Contraseña temporal
                'rol' => 'estudiante'
            ]);

            $estudiante->id_usuario = $user->id;
            $estudiante->save();
        }

        return redirect()->route('admin.estudiantes.index')
            ->with('success', 'Estudiante creado exitosamente.');
    }

    public function edit($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        return view('admin.estudiantes.edit', compact('estudiante'));
    }

    public function update(Request $request, $id)
    {
        $estudiante = Estudiante::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'cui' => 'nullable|string|max:20|unique:estudiantes,cui,' . $id,
            'telefono' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date'
        ]);

        $estudiante->update($request->all());

        return redirect()->route('admin.estudiantes.index')
            ->with('success', 'Estudiante actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $estudiante = Estudiante::findOrFail($id);

        // Eliminar inscripciones
        $estudiante->inscripciones()->delete();

        // Eliminar usuario asociado
        if ($estudiante->id_usuario) {
            User::find($estudiante->id_usuario)?->delete();
        }

        $estudiante->delete();

        return redirect()->route('admin.estudiantes.index')
            ->with('success', 'Estudiante eliminado exitosamente.');
    }

    public function inscribir($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $combinaciones = GradoSeccion::with(['grado', 'seccion'])->get();
        $inscripcionesAnteriores = $estudiante->inscripciones()
            ->with(['gradoSeccion.grado', 'gradoSeccion.seccion'])
            ->orderBy('anio', 'desc')
            ->get();

        return view('admin.estudiantes.inscribir', compact('estudiante', 'combinaciones', 'inscripcionesAnteriores'));
    }

    public function guardarInscripcion(Request $request, $id)
    {
        $estudiante = Estudiante::findOrFail($id);

        $request->validate([
            'id_grado_seccion' => 'required|exists:grado_seccion,id',
            'anio' => 'required|integer|min:2000|max:' . (date('Y') + 1)
        ]);

        // Desactivar inscripciones activas anteriores
        Inscripcion::where('id_estudiante', $estudiante->id)
            ->where('estado', 'activo')
            ->update(['estado' => 'inactivo']);

        // Crear nueva inscripción
        Inscripcion::create([
            'id_estudiante' => $estudiante->id,
            'id_grado_seccion' => $request->id_grado_seccion,
            'anio' => $request->anio,
            'estado' => 'activo'
        ]);

        return redirect()->route('admin.estudiantes.index')
            ->with('success', 'Estudiante inscrito exitosamente.');
    }
}
