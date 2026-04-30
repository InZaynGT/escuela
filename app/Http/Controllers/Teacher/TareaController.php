<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use App\Models\Tarea;
use App\Models\Periodo;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher');
    }

    public function index($idMateria)
    {
        $profesor = auth()->user()->profesor;

        // Verificar que la materia pertenezca al profesor
        $materia = $profesor->materias()
            ->with(['gradoSeccion.grado', 'gradoSeccion.seccion'])
            ->where('materias.id', $idMateria)
            ->first();

        if (!$materia) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'No tienes acceso a esta materia.');
        }

        $tareas = Tarea::where('id_materia', $idMateria)
            ->orderBy('created_at', 'desc')
            ->get();

        $periodos = Periodo::orderBy('anio', 'desc')->get();

        return view('teacher.tareas.index', compact('materia', 'tareas', 'periodos'));
    }

    public function create($idMateria)
    {
        $profesor = auth()->user()->profesor;

        $materia = $profesor->materias()
            ->where('materias.id', $idMateria)
            ->first();

        if (!$materia) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'No tienes acceso a esta materia.');
        }

        $periodos = Periodo::orderBy('anio', 'desc')->get();

        return view('teacher.tareas.create', compact('materia', 'periodos'));
    }

    public function store(Request $request, $idMateria)
    {
        $profesor = auth()->user()->profesor;

        $materia = $profesor->materias()
            ->where('materias.id', $idMateria)
            ->first();

        if (!$materia) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'No tienes acceso a esta materia.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'ponderacion' => 'required|numeric|min:0|max:100',
            'id_periodo' => 'required|exists:periodos,id'
        ]);

        Tarea::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'ponderacion' => $request->ponderacion,
            'id_periodo' => $request->id_periodo,
            'id_materia' => $idMateria
        ]);

        return redirect()->route('teacher.tareas.index', $idMateria)
            ->with('success', '¡Tarea creada exitosamente!');
    }

    public function edit($idMateria, $idTarea)
    {
        $profesor = auth()->user()->profesor;

        $materia = $profesor->materias()
            ->where('materias.id', $idMateria)
            ->first();

        if (!$materia) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'No tienes acceso a esta materia.');
        }

        $tarea = Tarea::where('id_materia', $idMateria)
            ->where('id', $idTarea)
            ->firstOrFail();

        $periodos = Periodo::orderBy('anio', 'desc')->get();

        return view('teacher.tareas.edit', compact('materia', 'tarea', 'periodos'));
    }

    public function update(Request $request, $idMateria, $idTarea)
    {
        $profesor = auth()->user()->profesor;

        $materia = $profesor->materias()
            ->where('materias.id', $idMateria)
            ->first();

        if (!$materia) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'No tienes acceso a esta materia.');
        }

        $tarea = Tarea::where('id_materia', $idMateria)
            ->where('id', $idTarea)
            ->firstOrFail();

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'ponderacion' => 'required|numeric|min:0|max:100',
            'id_periodo' => 'required|exists:periodos,id'
        ]);

        $tarea->update($request->all());

        return redirect()->route('teacher.tareas.index', $idMateria)
            ->with('success', '¡Tarea actualizada exitosamente!');
    }

    public function destroy($idMateria, $idTarea)
    {
        $profesor = auth()->user()->profesor;

        $materia = $profesor->materias()
            ->where('materias.id', $idMateria)
            ->first();

        if (!$materia) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'No tienes acceso a esta materia.');
        }

        $tarea = Tarea::where('id_materia', $idMateria)
            ->where('id', $idTarea)
            ->firstOrFail();

        $tarea->delete();

        return redirect()->route('teacher.tareas.index', $idMateria)
            ->with('success', '¡Tarea eliminada exitosamente!');
    }
}
