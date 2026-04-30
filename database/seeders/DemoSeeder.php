<?php

namespace Database\Seeders;

use App\Models\Asistencia;
use App\Models\Estudiante;
use App\Models\Grado;
use App\Models\GradoSeccion;
use App\Models\Inscripcion;
use App\Models\Materia;
use App\Models\Periodo;
use App\Models\Profesor;
use App\Models\Seccion;
use App\Models\Tarea;
use App\Models\TareaEstudiante;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    // ── Datos maestros ──────────────────────────────────────────────────────────

    private array $nombresGrados = [
        'Primero Primaria',
        'Segundo Primaria',
        'Tercero Primaria',
        'Cuarto Primaria',
        'Quinto Primaria',
        'Sexto Primaria',
    ];

    private array $periodosData = [
        ['nombre' => 'Primer Unidad',   'anio' => 2026, 'inicio' => '2026-01-15', 'fin' => '2026-03-28'],
        ['nombre' => 'Segundo Unidad',  'anio' => 2026, 'inicio' => '2026-04-07', 'fin' => '2026-06-27'],
        ['nombre' => 'Tercer Unidad',   'anio' => 2026, 'inicio' => '2026-07-07', 'fin' => '2026-09-26'],
        ['nombre' => 'Cuarto Unidad',   'anio' => 2026, 'inicio' => '2026-10-05', 'fin' => '2026-11-28'],
    ];

    // Docentes con sus asignaciones: qué materia (por nombre parcial) y en qué grados
    private array $docentesData = [
        [
            'nombre'    => 'Ana',
            'apellidos' => 'López Morales',
            'email'     => 'ana@escuela.com',
            'materia'   => 'Matemáticas',
            'grados'    => ['Primero Primaria', 'Segundo Primaria', 'Tercero Primaria'],
        ],
        [
            'nombre'    => 'Luis',
            'apellidos' => 'García Pérez',
            'email'     => 'luis@escuela.com',
            'materia'   => 'Comunicación y Lenguaje L1',
            'grados'    => ['Primero Primaria', 'Segundo Primaria', 'Cuarto Primaria', 'Quinto Primaria'],
        ],
        [
            'nombre'    => 'Rosa',
            'apellidos' => 'Hernández Ajú',
            'email'     => 'rosa@escuela.com',
            'materia'   => 'Comunicación y Lenguaje L2',
            'grados'    => ['Tercero Primaria', 'Cuarto Primaria', 'Quinto Primaria', 'Sexto Primaria'],
        ],
    ];

    // Estudiantes por sección (grado → sección → lista)
    private array $estudiantesData = [
        'Primero Primaria' => [
            'A' => [
                ['nombre' => 'Sofía',    'apellidos' => 'Ajú Tun',         'cui' => '2001000101'],
                ['nombre' => 'Diego',    'apellidos' => 'Caal Xo',         'cui' => '2001000102'],
                ['nombre' => 'Valeria',  'apellidos' => 'Morales Pérez',   'cui' => '2001000103'],
                ['nombre' => 'Mateo',    'apellidos' => 'Yac Choc',        'cui' => '2001000104'],
                ['nombre' => 'Gabriela', 'apellidos' => 'López Maquin',    'cui' => '2001000105'],
                ['nombre' => 'Andrés',   'apellidos' => 'Cojtí Sajquim',   'cui' => '2001000106'],
            ],
            'B' => [
                ['nombre' => 'Fernanda', 'apellidos' => 'Ixcoy Cúmes',     'cui' => '2001000201'],
                ['nombre' => 'Ricardo',  'apellidos' => 'Toc Velásquez',   'cui' => '2001000202'],
                ['nombre' => 'Lucía',    'apellidos' => 'Pu Cotom',        'cui' => '2001000203'],
                ['nombre' => 'Samuel',   'apellidos' => 'Tzul Mox',        'cui' => '2001000204'],
            ],
        ],
        'Segundo Primaria' => [
            'A' => [
                ['nombre' => 'Daniela',  'apellidos' => 'Chumil Guarchaj', 'cui' => '2002000101'],
                ['nombre' => 'José',     'apellidos' => 'Panjoj Mejía',    'cui' => '2002000102'],
                ['nombre' => 'Andrea',   'apellidos' => 'Tuy Batz',        'cui' => '2002000103'],
                ['nombre' => 'Cristian', 'apellidos' => 'Tzay Sipac',      'cui' => '2002000104'],
                ['nombre' => 'Paola',    'apellidos' => 'Mendoza Sac',     'cui' => '2002000105'],
            ],
        ],
        'Tercero Primaria' => [
            'A' => [
                ['nombre' => 'Kevin',    'apellidos' => 'Batz Xiloj',      'cui' => '2003000101'],
                ['nombre' => 'Jennifer', 'apellidos' => 'Chávez Ixcoy',    'cui' => '2003000102'],
                ['nombre' => 'Bryan',    'apellidos' => 'Saquimux Ixcoy',  'cui' => '2003000103'],
            ],
        ],
        'Cuarto Primaria' => [
            'A' => [
                ['nombre' => 'Marta',    'apellidos' => 'Oxlaj Quiacaín',  'cui' => '2004000101'],
                ['nombre' => 'Héctor',   'apellidos' => 'Yax Chávez',      'cui' => '2004000102'],
                ['nombre' => 'Wendy',    'apellidos' => 'Bixcul Cotom',    'cui' => '2004000103'],
            ],
        ],
        'Quinto Primaria' => [
            'A' => [
                ['nombre' => 'Pablo',    'apellidos' => 'Camey Velásquez', 'cui' => '2005000101'],
                ['nombre' => 'Claudia',  'apellidos' => 'Choc Caal',       'cui' => '2005000102'],
            ],
        ],
        'Sexto Primaria' => [
            'A' => [
                ['nombre' => 'Rebeca',   'apellidos' => 'Ajú Saquimux',    'cui' => '2006000101'],
                ['nombre' => 'Carlos',   'apellidos' => 'Toc Batz',        'cui' => '2006000102'],
            ],
        ],
    ];

    // ── Punto de entrada ────────────────────────────────────────────────────────

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // El migrate:fresh ya truncó las tablas, pero por si acaso
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $periodos        = $this->crearPeriodos();
        $gradoSecciones  = $this->crearEstructura();     // observer siembra materias
        $profesores      = $this->crearProfesores($gradoSecciones);
        $estudiantes     = $this->crearEstudiantes($gradoSecciones);
        $tareas          = $this->crearTareas($profesores, $periodos);
        $this->crearCalificaciones($estudiantes, $tareas);
        $this->crearAsistencias($estudiantes, $gradoSecciones, $periodos);
    }

    // ── Periodos ────────────────────────────────────────────────────────────────

    private function crearPeriodos(): \Illuminate\Support\Collection
    {
        $periodos = collect();
        foreach ($this->periodosData as $d) {
            $periodos->push(Periodo::create([
                'nombre'      => $d['nombre'],
                'anio'        => $d['anio'],
                'fecha_inicio' => $d['inicio'],
                'fecha_fin'   => $d['fin'],
            ]));
        }
        return $periodos;
    }

    // ── Grados / Secciones / GradoSecciones ─────────────────────────────────────

    private function crearEstructura(): array
    {
        $seccionA = Seccion::create(['nombre' => 'A']);
        $seccionB = Seccion::create(['nombre' => 'B']);

        $gradoSecciones = [];

        foreach ($this->nombresGrados as $nombre) {
            $grado = Grado::create(['nombre' => $nombre]);

            // Sección A para todos los grados
            $gsA = GradoSeccion::create(['id_grado' => $grado->id, 'id_seccion' => $seccionA->id]);
            $gradoSecciones[$nombre]['A'] = $gsA;

            // Sección B solo para Primero y Segundo
            if (in_array($nombre, ['Primero Primaria', 'Segundo Primaria'])) {
                $gsB = GradoSeccion::create(['id_grado' => $grado->id, 'id_seccion' => $seccionB->id]);
                $gradoSecciones[$nombre]['B'] = $gsB;
            }
        }

        return $gradoSecciones;
    }

    // ── Profesores ───────────────────────────────────────────────────────────────

    private function crearProfesores(array $gradoSecciones): array
    {
        // Admin
        User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@escuela.com',
            'password' => bcrypt('admin123'),
            'rol'      => 'admin',
        ]);

        $profesores = [];

        foreach ($this->docentesData as $d) {
            $user = User::create([
                'name'     => "{$d['nombre']} {$d['apellidos']}",
                'email'    => $d['email'],
                'password' => bcrypt('docente123'),
                'rol'      => 'docente',
            ]);

            $profesor = Profesor::create([
                'id_usuario' => $user->id,
                'nombre'     => $d['nombre'],
                'apellidos'  => $d['apellidos'],
                'telefono'   => '5' . rand(1000000, 9999999),
            ]);

            // Asignar materias según configuración
            foreach ($d['grados'] as $nombreGrado) {
                foreach (['A', 'B'] as $sec) {
                    if (!isset($gradoSecciones[$nombreGrado][$sec])) {
                        continue;
                    }
                    $gs = $gradoSecciones[$nombreGrado][$sec];
                    $materia = Materia::where('id_grado_seccion', $gs->id)
                        ->where('nombre', 'like', '%' . $d['materia'] . '%')
                        ->first();
                    if ($materia) {
                        $profesor->materias()->attach($materia->id);
                    }
                }
            }

            $profesores[] = ['profesor' => $profesor, 'config' => $d];
        }

        return $profesores;
    }

    // ── Estudiantes + Inscripciones ──────────────────────────────────────────────

    private function crearEstudiantes(array $gradoSecciones): array
    {
        $resultado = [];
        $emailIdx  = 1;

        foreach ($this->estudiantesData as $nombreGrado => $secciones) {
            foreach ($secciones as $secLetra => $lista) {
                if (!isset($gradoSecciones[$nombreGrado][$secLetra])) {
                    continue;
                }
                $gs = $gradoSecciones[$nombreGrado][$secLetra];

                foreach ($lista as $dato) {
                    $user = User::create([
                        'name'     => "{$dato['nombre']} {$dato['apellidos']}",
                        'email'    => "est{$emailIdx}@escuela.com",
                        'password' => bcrypt('est123'),
                        'rol'      => 'estudiante',
                    ]);
                    $emailIdx++;

                    $estudiante = Estudiante::create([
                        'id_usuario'      => $user->id,
                        'nombre'          => $dato['nombre'],
                        'apellidos'       => $dato['apellidos'],
                        'cui'             => $dato['cui'],
                        'fecha_nacimiento' => $this->fechaNacimientoAleatoria(),
                    ]);

                    Inscripcion::create([
                        'id_estudiante'    => $estudiante->id,
                        'id_grado_seccion' => $gs->id,
                        'anio'             => 2026,
                        'estado'           => 'activo',
                    ]);

                    $resultado[$gs->id][] = $estudiante;
                }
            }
        }

        return $resultado;
    }

    // ── Tareas ───────────────────────────────────────────────────────────────────

    private function crearTareas(array $profesores, \Illuminate\Support\Collection $periodos): array
    {
        // 3 tareas por periodo con ponderaciones que suman 100
        $plantillas = [
            ['titulo' => 'Actividades y participación', 'ponderacion' => 30],
            ['titulo' => 'Examen bimestral',             'ponderacion' => 40],
            ['titulo' => 'Trabajo y cuaderno',           'ponderacion' => 30],
        ];

        $tareasPorMateria = [];

        foreach ($profesores as $item) {
            foreach ($item['profesor']->materias as $materia) {
                $tareasMateria = [];
                foreach ($periodos as $periodo) {
                    foreach ($plantillas as $tpl) {
                        $tarea = Tarea::create([
                            'titulo'       => $tpl['titulo'],
                            'ponderacion'  => $tpl['ponderacion'],
                            'id_materia'   => $materia->id,
                            'id_periodo'   => $periodo->id,
                        ]);
                        $tareasMateria[] = $tarea;
                    }
                }
                $tareasPorMateria[$materia->id] = $tareasMateria;
            }
        }

        return $tareasPorMateria;
    }

    // ── Calificaciones ───────────────────────────────────────────────────────────

    private function crearCalificaciones(array $estudiantesPorGs, array $tareasPorMateria): void
    {
        foreach ($estudiantesPorGs as $gsId => $estudiantes) {
            // Materias de este grado-sección que tienen tareas
            $materias = Materia::where('id_grado_seccion', $gsId)
                ->whereIn('id', array_keys($tareasPorMateria))
                ->get();

            foreach ($estudiantes as $estudiante) {
                // Nivel de rendimiento aleatorio por estudiante (60-95%)
                $nivel = mt_rand(60, 95) / 100;

                foreach ($materias as $materia) {
                    $tareas = $tareasPorMateria[$materia->id] ?? [];

                    // Solo calificar los dos primeros periodos (Unidads ya pasados)
                    // Las tareas están ordenadas: periodo1×3, periodo2×3, periodo3×3, periodo4×3
                    $tareasPasadas = array_slice($tareas, 0, 6); // 2 periodos × 3 tareas

                    foreach ($tareasPasadas as $tarea) {
                        $maxPts = $tarea->ponderacion;
                        // Variación individual ±10% sobre el nivel base
                        $factor = $nivel + (mt_rand(-10, 10) / 100);
                        $factor = max(0.5, min(1.0, $factor));
                        $nota   = round($maxPts * $factor, 1);

                        TareaEstudiante::create([
                            'id_estudiante' => $estudiante->id,
                            'id_tarea'      => $tarea->id,
                            'calificacion'  => $nota,
                            'calificado'    => 1,
                            'entrego'       => 1,
                        ]);
                    }
                }
            }
        }
    }

    // ── Asistencias ──────────────────────────────────────────────────────────────

    private function crearAsistencias(
        array $estudiantesPorGs,
        array $gradoSecciones,
        \Illuminate\Support\Collection $periodos
    ): void {
        $primerPeriodo = $periodos->first();
        $diasEscolares = $this->diasHabilesEnRango($primerPeriodo->fecha_inicio, '2026-04-18');

        // Estados con probabilidad: 80% presente, 10% tardanza, 7% ausente, 3% justificado
        $pool = array_merge(
            array_fill(0, 80, 'presente'),
            array_fill(0, 10, 'tardanza'),
            array_fill(0, 7,  'ausente'),
            array_fill(0, 3,  'justificado')
        );

        foreach ($estudiantesPorGs as $gsId => $estudiantes) {
            // Buscar a qué GradoSeccion pertenece este gsId
            $periodoId = $primerPeriodo->id;

            foreach ($estudiantes as $estudiante) {
                foreach ($diasEscolares as $fecha) {
                    $estado = $pool[array_rand($pool)];
                    Asistencia::create([
                        'id_estudiante'    => $estudiante->id,
                        'id_grado_seccion' => $gsId,
                        'id_periodo'       => $periodoId,
                        'fecha'            => $fecha,
                        'estado'           => $estado,
                        'observacion'      => null,
                    ]);
                }
            }
        }
    }

    // ── Helpers ──────────────────────────────────────────────────────────────────

    private function fechaNacimientoAleatoria(): string
    {
        $year = mt_rand(2015, 2019);
        $month = str_pad(mt_rand(1, 12), 2, '0', STR_PAD_LEFT);
        $day   = str_pad(mt_rand(1, 28), 2, '0', STR_PAD_LEFT);
        return "{$year}-{$month}-{$day}";
    }

    private function diasHabilesEnRango(string $inicio, string $fin): array
    {
        $dias  = [];
        $fecha = Carbon::parse($inicio);
        $hasta = Carbon::parse($fin);

        while ($fecha->lte($hasta)) {
            if ($fecha->isWeekday()) {
                $dias[] = $fecha->format('Y-m-d');
            }
            $fecha->addDay();
        }

        return $dias;
    }
}
