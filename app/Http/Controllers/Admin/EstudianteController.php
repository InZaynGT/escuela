<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\EstudiantesImport;
use App\Exports\PlantillaEstudiantesExport;
use App\Models\Estudiante;
use App\Models\User;
use App\Models\GradoSeccion;
use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EstudianteController extends Controller
{
    public function index(Request $request)
    {
        $gradoSecciones = GradoSeccion::with('grado', 'seccion')
            ->get()
            ->sortBy(fn($gs) => $gs->grado->id);

        $idGradoSeccion = $request->get('id_grado_seccion');
        $idGrado        = $request->get('id_grado');
        $nombre         = $request->get('nombre');

        $estudiantes = null;

        if ($idGradoSeccion || $idGrado) {
            $query = Estudiante::whereHas('inscripciones', function ($q) use ($idGradoSeccion, $idGrado, $gradoSecciones) {
                $q->where('anio', date('Y'))->where('estado', 'activo');
                if ($idGradoSeccion) {
                    $q->where('id_grado_seccion', $idGradoSeccion);
                } else {
                    $gsIds = $gradoSecciones->where('id_grado', (int) $idGrado)->pluck('id');
                    $q->whereIn('id_grado_seccion', $gsIds);
                }
            })->with([
                'inscripciones' => fn($q) => $q->where('estado', 'activo')
                    ->with('gradoSeccion.grado', 'gradoSeccion.seccion'),
            ])->orderBy('apellidos');

            if ($nombre) {
                $query->where(function ($q) use ($nombre) {
                    $q->where('nombre', 'like', "%{$nombre}%")
                      ->orWhere('apellidos', 'like', "%{$nombre}%");
                });
            }

            $estudiantes = $query->paginate(50)->withQueryString();
        }

        return view('admin.estudiantes.index', compact('estudiantes', 'gradoSecciones'));
    }

    public function apiListar(Request $request)
    {
        $idGradoSeccion = $request->get('id_grado_seccion');
        $idGrado        = $request->get('id_grado');

        if (!$idGradoSeccion && !$idGrado) {
            return response()->json([]);
        }

        $estudiantes = Estudiante::whereHas('inscripciones', function ($q) use ($idGradoSeccion, $idGrado) {
            $q->where('anio', date('Y'))->where('estado', 'activo');
            if ($idGradoSeccion) {
                $q->where('id_grado_seccion', $idGradoSeccion);
            } else {
                $gsIds = GradoSeccion::where('id_grado', $idGrado)->pluck('id');
                $q->whereIn('id_grado_seccion', $gsIds);
            }
        })->orderBy('apellidos')->get();

        return response()->json(
            $estudiantes->map(fn($e) => ['id' => $e->id, 'texto' => $e->apellidos . ', ' . $e->nombre])
        );
    }

    public function create()
    {
        return view('admin.estudiantes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'apellidos'        => 'required|string|max:255',
            'cui'              => 'required|string|max:20|unique:estudiantes,cui',
            'telefono'         => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
        ]);

        $estudiante = Estudiante::create($request->only([
            'nombre', 'apellidos', 'cui', 'telefono', 'fecha_nacimiento',
        ]));

        $usuario = $this->crearCuentaEstudiante($estudiante);

        $msg = 'Estudiante registrado.';
        if ($usuario) {
            $msg .= " Cuenta creada — Usuario: <strong>{$usuario}</strong> / Contraseña: CUI del estudiante.";
        }

        return redirect()->route('admin.estudiantes.index')->with('success', $msg);
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
            'nombre'           => 'required|string|max:255',
            'apellidos'        => 'required|string|max:255',
            'cui'              => 'required|string|max:20|unique:estudiantes,cui,' . $id,
            'telefono'         => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
        ]);

        $estudiante->update($request->only(['nombre', 'apellidos', 'cui', 'telefono', 'fecha_nacimiento']));

        return redirect()->route('admin.estudiantes.index')
            ->with('success', 'Estudiante actualizado exitosamente.');
    }

    public function cuenta($id)
    {
        $estudiante = Estudiante::with('user')->findOrFail($id);
        return view('admin.estudiantes.cuenta', compact('estudiante'));
    }

    public function actualizarCuenta(Request $request, $id)
    {
        $estudiante = Estudiante::with('user')->findOrFail($id);

        $request->validate([
            'email'                 => 'required|email|unique:users,email,' . ($estudiante->id_usuario ?? 'NULL'),
            'password'              => 'nullable|string|min:6|confirmed',
        ]);

        if ($estudiante->user) {
            $datos = ['email' => $request->email, 'name' => $estudiante->nombre_completo];
            if ($request->filled('password')) {
                $datos['password'] = bcrypt($request->password);
            }
            $estudiante->user->update($datos);
        } else {
            $user = User::create([
                'name'     => $estudiante->nombre_completo,
                'email'    => $request->email,
                'password' => bcrypt($request->filled('password') ? $request->password : 'estudiante123'),
                'rol'      => 'estudiante',
            ]);
            $estudiante->update(['id_usuario' => $user->id]);
        }

        return redirect()->route('admin.estudiantes.cuenta', $id)
            ->with('success', 'Cuenta actualizada correctamente.');
    }

    public function destroy($id)
    {
        $estudiante = Estudiante::findOrFail($id);

        $estudiante->tareaEstudiantes()->delete();
        $estudiante->calificacionesFinales()->delete();
        $estudiante->responsables()->detach();
        $estudiante->inscripciones()->delete();

        if ($estudiante->id_usuario) {
            User::find($estudiante->id_usuario)?->delete();
        }

        $estudiante->delete();

        return redirect()->route('admin.estudiantes.index')
            ->with('success', 'Estudiante eliminado exitosamente.');
    }

    public function generarCuentas()
    {
        $sinCuenta = Estudiante::whereNull('id_usuario')
            ->whereNotNull('cui')
            ->where('cui', '!=', '')
            ->get();

        $creadas = 0;
        foreach ($sinCuenta as $est) {
            if ($this->crearCuentaEstudiante($est) !== null) {
                $creadas++;
            }
        }

        return redirect()->route('admin.estudiantes.index')
            ->with('success', "Cuentas generadas: {$creadas}. Usuario = nombre.apellido / Contraseña = CUI del estudiante.");
    }

    private function crearCuentaEstudiante(Estudiante $estudiante): ?string
    {
        if ($estudiante->id_usuario || !$estudiante->cui) {
            return null;
        }

        $email = User::generarEmailEstudiante($estudiante->nombre, $estudiante->apellidos);

        $user = User::create([
            'name'     => $estudiante->nombre_completo,
            'email'    => $email,
            'password' => bcrypt(trim($estudiante->cui)),
            'rol'      => 'estudiante',
        ]);

        $estudiante->update(['id_usuario' => $user->id]);

        return str_replace('@eorm.local', '', $email);
    }

    public function importarForm()
    {
        $gradoSecciones = GradoSeccion::with('grado', 'seccion')
            ->get()->sortBy(fn($gs) => $gs->grado->id);

        return view('admin.estudiantes.importar', compact('gradoSecciones'));
    }

    public function importar(Request $request)
    {
        $request->validate([
            'id_grado_seccion' => 'required|exists:grado_seccion,id',
            'anio'             => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'archivo'          => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        $import = new EstudiantesImport(
            (int) $request->id_grado_seccion,
            (int) $request->anio
        );

        Excel::import($import, $request->file('archivo'));

        $gs  = GradoSeccion::with('grado', 'seccion')->find($request->id_grado_seccion);
        $msg = "Importación completada: {$import->importados} estudiante(s) inscrito(s) en "
             . "{$gs->grado->nombre} {$gs->seccion->nombre} ({$request->anio}).";

        if ($import->omitidos > 0) {
            $msg .= " {$import->omitidos} fila(s) omitida(s).";
        }

        return redirect()->route('admin.estudiantes.importar')
            ->with('success', $msg)
            ->with('advertencias', $import->advertencias);
    }

    public function descargarPlantilla()
    {
        return Excel::download(new PlantillaEstudiantesExport(), 'plantilla_estudiantes.xlsx');
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
