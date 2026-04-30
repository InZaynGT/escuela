<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use App\Models\Tarea;
use App\Models\TareaEstudiante;
use App\Models\Periodo;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('teacher');
    }

    // Vista de periodos/unidades de una materia
    public function show($idMateria)
    {
        $profesor = auth()->user()->profesor;

        $materia = $profesor->materias()
            ->with(['gradoSeccion.grado', 'gradoSeccion.seccion'])
            ->where('materias.id', $idMateria)
            ->first();

        if (!$materia) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'No tienes acceso a esta materia.');
        }

        $periodos = Periodo::orderBy('id')->get();

        $puntosUsadosPorPeriodo = Tarea::where('id_materia', $idMateria)
            ->selectRaw('id_periodo, SUM(ponderacion) as total')
            ->groupBy('id_periodo')
            ->pluck('total', 'id_periodo');

        $tareasPorPeriodo = Tarea::where('id_materia', $idMateria)
            ->selectRaw('id_periodo, COUNT(*) as cantidad')
            ->groupBy('id_periodo')
            ->pluck('cantidad', 'id_periodo');

        return view('teacher.materias.show', compact(
            'materia', 'periodos', 'puntosUsadosPorPeriodo', 'tareasPorPeriodo'
        ));
    }

    // Lista de tareas de un periodo específico
    public function index(Request $request, $idMateria)
    {
        $profesor = auth()->user()->profesor;

        $materia = $profesor->materias()
            ->with(['gradoSeccion.grado', 'gradoSeccion.seccion'])
            ->where('materias.id', $idMateria)
            ->first();

        if (!$materia) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'No tienes acceso a esta materia.');
        }

        $idPeriodo = $request->get('id_periodo');

        if (!$idPeriodo) {
            return redirect()->route('teacher.materias.show', $idMateria);
        }

        $periodo = Periodo::findOrFail($idPeriodo);

        $tareas = Tarea::where('id_materia', $idMateria)
            ->where('id_periodo', $idPeriodo)
            ->orderBy('created_at')
            ->get();

        $ptsUsados = $tareas->sum('ponderacion');

        return view('teacher.tareas.index', compact(
            'materia', 'periodo', 'tareas', 'ptsUsados'
        ));
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

        $periodos = Periodo::orderBy('id')->get();

        $puntosUsadosPorPeriodo = Tarea::where('id_materia', $idMateria)
            ->selectRaw('id_periodo, SUM(ponderacion) as total')
            ->groupBy('id_periodo')
            ->pluck('total', 'id_periodo');

        return view('teacher.tareas.create', compact('materia', 'periodos', 'puntosUsadosPorPeriodo'));
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
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'ponderacion' => 'required|numeric|min:1|max:100',
            'id_periodo'  => 'required|exists:periodos,id',
        ]);

        $periodo = Periodo::findOrFail($request->id_periodo);
        if ($periodo->bloqueado) {
            return back()->withInput()
                ->with('error', "El período \"{$periodo->nombre}\" está bloqueado. No se pueden crear tareas.");
        }

        $usados = Tarea::where('id_materia', $idMateria)
            ->where('id_periodo', $request->id_periodo)
            ->sum('ponderacion');

        $disponibles = 100 - $usados;
        if ($request->ponderacion > $disponibles) {
            return back()->withErrors([
                'ponderacion' => "Solo quedan {$disponibles} pts disponibles para este periodo.",
            ])->withInput();
        }

        Tarea::create([
            'titulo'      => $request->titulo,
            'descripcion' => $request->descripcion,
            'ponderacion' => $request->ponderacion,
            'id_periodo'  => $request->id_periodo,
            'id_materia'  => $idMateria,
        ]);

        return redirect()->route('teacher.tareas.index', ['idMateria' => $idMateria, 'id_periodo' => $request->id_periodo])
            ->with('success', 'Tarea creada exitosamente.');
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

        if ($tarea->periodo && $tarea->periodo->bloqueado) {
            return redirect()->route('teacher.tareas.index', ['idMateria' => $idMateria, 'id_periodo' => $tarea->id_periodo])
                ->with('error', "El período \"{$tarea->periodo->nombre}\" está bloqueado. No se puede editar esta tarea.");
        }

        $periodos = Periodo::orderBy('id')->get();

        $puntosUsadosPorPeriodo = Tarea::where('id_materia', $idMateria)
            ->where('id', '!=', $idTarea)
            ->selectRaw('id_periodo, SUM(ponderacion) as total')
            ->groupBy('id_periodo')
            ->pluck('total', 'id_periodo');

        return view('teacher.tareas.edit', compact('materia', 'tarea', 'periodos', 'puntosUsadosPorPeriodo'));
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
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'ponderacion' => 'required|numeric|min:1|max:100',
            'id_periodo'  => 'required|exists:periodos,id',
        ]);

        $periodo = Periodo::findOrFail($request->id_periodo);
        if ($periodo->bloqueado) {
            return back()->withInput()
                ->with('error', "El período \"{$periodo->nombre}\" está bloqueado. No se puede modificar esta tarea.");
        }

        $usados = Tarea::where('id_materia', $idMateria)
            ->where('id_periodo', $request->id_periodo)
            ->where('id', '!=', $idTarea)
            ->sum('ponderacion');

        $disponibles = 100 - $usados;
        if ($request->ponderacion > $disponibles) {
            return back()->withErrors([
                'ponderacion' => "Solo quedan {$disponibles} pts disponibles para este periodo.",
            ])->withInput();
        }

        $tarea->update($request->only(['titulo', 'descripcion', 'ponderacion', 'id_periodo']));

        return redirect()->route('teacher.tareas.index', ['idMateria' => $idMateria, 'id_periodo' => $request->id_periodo])
            ->with('success', 'Tarea actualizada exitosamente.');
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

        $idPeriodo = $tarea->id_periodo;

        if ($tarea->periodo && $tarea->periodo->bloqueado) {
            return redirect()->route('teacher.tareas.index', ['idMateria' => $idMateria, 'id_periodo' => $idPeriodo])
                ->with('error', "El período \"{$tarea->periodo->nombre}\" está bloqueado. No se puede eliminar esta tarea.");
        }

        if (TareaEstudiante::where('id_tarea', $tarea->id)->exists()) {
            return redirect()->route('teacher.tareas.index', ['idMateria' => $idMateria, 'id_periodo' => $idPeriodo])
                ->with('error', 'No se puede eliminar esta tarea porque ya tiene calificaciones registradas.');
        }

        $tarea->delete();

        return redirect()->route('teacher.tareas.index', ['idMateria' => $idMateria, 'id_periodo' => $idPeriodo])
            ->with('success', 'Tarea eliminada exitosamente.');
    }
}
