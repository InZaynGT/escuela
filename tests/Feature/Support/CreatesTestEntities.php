<?php

namespace Tests\Feature\Support;

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
use App\Models\User;

trait CreatesTestEntities
{
    protected function createAdmin(): User
    {
        return User::create([
            'name'     => 'Admin Test',
            'email'    => 'admin@test.local',
            'password' => bcrypt('password'),
            'rol'      => 'admin',
        ]);
    }

    /** @return array{0: User, 1: Profesor} */
    protected function createTeacher(string $email = 'docente@test.local'): array
    {
        $user = User::create([
            'name'     => 'Docente Test',
            'email'    => $email,
            'password' => bcrypt('password'),
            'rol'      => 'docente',
        ]);
        $profesor = Profesor::create([
            'nombre'     => 'Carlos',
            'apellidos'  => 'Pérez',
            'id_usuario' => $user->id,
        ]);
        return [$user, $profesor];
    }

    /** @return array{0: User, 1: Estudiante} */
    protected function createStudent(string $email = 'estudiante@test.local'): array
    {
        $user = User::create([
            'name'     => 'Estudiante Test',
            'email'    => $email,
            'password' => bcrypt('password'),
            'rol'      => 'estudiante',
        ]);
        $estudiante = Estudiante::create([
            'nombre'     => 'María',
            'apellidos'  => 'López',
            'id_usuario' => $user->id,
        ]);
        return [$user, $estudiante];
    }

    protected function createGradoSeccion(string $grado = 'Primero Primaria', string $seccion = 'A'): GradoSeccion
    {
        $g = Grado::create(['nombre' => $grado]);
        $s = Seccion::create(['nombre' => $seccion]);
        return GradoSeccion::create(['id_grado' => $g->id, 'id_seccion' => $s->id]);
    }

    protected function createMateria(int $idGradoSeccion, string $nombre = 'Matemáticas'): Materia
    {
        return Materia::create(['nombre' => $nombre, 'id_grado_seccion' => $idGradoSeccion]);
    }

    protected function createPeriodo(array $attrs = []): Periodo
    {
        return Periodo::create(array_merge([
            'nombre'       => 'I Unidad',
            'anio'         => date('Y'),
            'fecha_inicio' => now()->startOfMonth()->toDateString(),
            'fecha_fin'    => now()->endOfMonth()->toDateString(),
            'bloqueado'    => false,
        ], $attrs));
    }

    protected function createTarea(int $idMateria, int $idPeriodo, string $titulo = 'Tarea 1'): Tarea
    {
        return Tarea::create([
            'titulo'      => $titulo,
            'ponderacion' => 10,
            'id_materia'  => $idMateria,
            'id_periodo'  => $idPeriodo,
        ]);
    }

    protected function createInscripcion(int $idEstudiante, int $idGradoSeccion): Inscripcion
    {
        return Inscripcion::create([
            'id_estudiante'    => $idEstudiante,
            'id_grado_seccion' => $idGradoSeccion,
            'anio'             => date('Y'),
            'estado'           => 'activo',
        ]);
    }

    protected function createAsistencia(int $idEstudiante, int $idGradoSeccion, string $fecha, string $estado = 'presente'): Asistencia
    {
        return Asistencia::create([
            'id_estudiante'    => $idEstudiante,
            'id_grado_seccion' => $idGradoSeccion,
            'fecha'            => $fecha,
            'estado'           => $estado,
        ]);
    }
}
