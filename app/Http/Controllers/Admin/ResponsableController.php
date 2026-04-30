<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\Responsable;
use Illuminate\Http\Request;

class ResponsableController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $responsables = Responsable::withCount('estudiantes')->orderBy('apellidos')->paginate(50);
        return view('admin.responsables.index', compact('responsables'));
    }

    public function create()
    {
        return view('admin.responsables.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'     => 'required|string|max:100',
            'apellidos'  => 'required|string|max:100',
            'telefono'   => 'nullable|string|max:20',
            'parentesco' => 'nullable|string|max:50',
        ]);

        Responsable::create($request->only('nombre', 'apellidos', 'telefono', 'parentesco'));

        return redirect()->route('admin.responsables.index')
            ->with('success', 'Responsable registrado correctamente.');
    }

    public function edit($id)
    {
        $responsable = Responsable::with('estudiantes')->findOrFail($id);
        return view('admin.responsables.edit', compact('responsable'));
    }

    public function update(Request $request, $id)
    {
        $responsable = Responsable::findOrFail($id);

        $request->validate([
            'nombre'     => 'required|string|max:100',
            'apellidos'  => 'required|string|max:100',
            'telefono'   => 'nullable|string|max:20',
            'parentesco' => 'nullable|string|max:50',
        ]);

        $responsable->update($request->only('nombre', 'apellidos', 'telefono', 'parentesco'));

        return redirect()->route('admin.responsables.index')
            ->with('success', 'Responsable actualizado.');
    }

    public function destroy($id)
    {
        $responsable = Responsable::findOrFail($id);
        $responsable->estudiantes()->detach();
        $responsable->delete();

        return redirect()->route('admin.responsables.index')
            ->with('success', 'Responsable eliminado.');
    }

    public function asignar($id)
    {
        $responsable = Responsable::with('estudiantes')->findOrFail($id);
        $estudiantes = Estudiante::orderBy('apellidos')->get();
        $asignados   = $responsable->estudiantes->pluck('id')->toArray();

        return view('admin.responsables.asignar', compact('responsable', 'estudiantes', 'asignados'));
    }

    public function guardarAsignacion(Request $request, $id)
    {
        $responsable = Responsable::findOrFail($id);

        $request->validate([
            'estudiantes'   => 'nullable|array',
            'estudiantes.*' => 'exists:estudiantes,id',
        ]);

        $responsable->estudiantes()->sync($request->estudiantes ?? []);

        return redirect()->route('admin.responsables.index')
            ->with('success', 'Estudiantes asignados correctamente.');
    }
}
