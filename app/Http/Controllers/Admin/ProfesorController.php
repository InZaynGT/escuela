<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradoSeccion;
use App\Models\Profesor;
use App\Models\User;
use App\Models\Materia;
use Illuminate\Http\Request;

class ProfesorController extends Controller
{
    public function index()
    {
        $profesores = Profesor::with('materias')->orderBy('apellidos')->paginate(50);
        return view('admin.profesores.index', compact('profesores'));
    }
    
    public function create()
    {
        return view('admin.profesores.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:users,email'
        ]);
        
        // Crear profesor
        $profesor = Profesor::create($request->only(['nombre', 'apellidos', 'telefono']));
        
        // Si se proporcionó email, crear usuario
        if ($request->filled('email')) {
            $user = User::create([
                'name' => $request->nombre . ' ' . $request->apellidos,
                'email' => $request->email,
                'password' => bcrypt('docente123'), // Contraseña temporal
                'rol' => 'docente'
            ]);
            
            $profesor->id_usuario = $user->id;
            $profesor->save();
        }
        
        return redirect()->route('admin.profesores.index')
            ->with('success', 'Profesor creado exitosamente.');
    }
    
    public function edit($id)
    {
        $profesor = Profesor::findOrFail($id);
        return view('admin.profesores.edit', compact('profesor'));
    }
    
    public function update(Request $request, $id)
    {
        $profesor = Profesor::findOrFail($id);

        $request->validate([
            'nombre'    => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono'  => 'nullable|string|max:20',
        ]);

        $profesor->update($request->only(['nombre', 'apellidos', 'telefono']));

        return redirect()->route('admin.profesores.index')
            ->with('success', 'Profesor actualizado exitosamente.');
    }

    public function cuenta($id)
    {
        $profesor = Profesor::with('user')->findOrFail($id);
        return view('admin.profesores.cuenta', compact('profesor'));
    }

    public function actualizarCuenta(Request $request, $id)
    {
        $profesor = Profesor::with('user')->findOrFail($id);

        $request->validate([
            'email'    => 'required|email|unique:users,email,' . ($profesor->id_usuario ?? 'NULL'),
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($profesor->user) {
            $datos = ['email' => $request->email, 'name' => $profesor->nombre_completo];
            if ($request->filled('password')) {
                $datos['password'] = bcrypt($request->password);
            }
            $profesor->user->update($datos);
        } else {
            $user = User::create([
                'name'     => $profesor->nombre_completo,
                'email'    => $request->email,
                'password' => bcrypt($request->filled('password') ? $request->password : 'docente123'),
                'rol'      => 'docente',
            ]);
            $profesor->update(['id_usuario' => $user->id]);
        }

        return redirect()->route('admin.profesores.cuenta', $id)
            ->with('success', 'Cuenta actualizada correctamente.');
    }
    
    public function destroy($id)
    {
        $profesor = Profesor::findOrFail($id);
        
        // Si tiene usuario asociado, eliminarlo también
        if ($profesor->id_usuario) {
            User::find($profesor->id_usuario)?->delete();
        }
        
        $profesor->delete();
        
        return redirect()->route('admin.profesores.index')
            ->with('success', 'Profesor eliminado exitosamente.');
    }
    
    public function asignar($id)
    {
        $profesor = Profesor::findOrFail($id);
        $materiasAsignadas = $profesor->materias()->pluck('materias.id')->toArray();

        $gradoSecciones = GradoSeccion::with([
            'grado',
            'seccion',
            'materias.profesores',
        ])->get()->sortBy(fn($gs) => ($gs->grado->id) . ($gs->seccion->nombre));

        return view('admin.profesores.asignar', compact('profesor', 'gradoSecciones', 'materiasAsignadas'));
    }
    
    public function guardarAsignacion(Request $request, $id)
    {
        $profesor = Profesor::findOrFail($id);
        
        $request->validate([
            'materias' => 'array',
            'materias.*' => 'exists:materias,id'
        ]);
        
        $profesor->materias()->sync($request->materias ?? []);
        
        return redirect()->route('admin.profesores.index')
            ->with('success', 'Materias asignadas exitosamente.');
    }
}