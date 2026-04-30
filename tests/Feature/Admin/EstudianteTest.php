<?php

namespace Tests\Feature\Admin;

use App\Models\Estudiante;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Support\CreatesTestEntities;
use Tests\TestCase;

class EstudianteTest extends TestCase
{
    use RefreshDatabase, CreatesTestEntities;

    private const STORE_URL = '/admin/estudiantes';

    public function test_admin_can_create_estudiante(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
             ->post(self::STORE_URL, [
                 'nombre'    => 'Juan',
                 'apellidos' => 'Pérez',
                 'cui'       => '1234567890101',
             ])
             ->assertRedirect(route('admin.estudiantes.index'));

        $this->assertDatabaseHas('estudiantes', [
            'nombre'    => 'Juan',
            'apellidos' => 'Pérez',
            'cui'       => '1234567890101',
        ]);
    }

    public function test_create_estudiante_also_creates_user_account(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)->post(self::STORE_URL, [
            'nombre'    => 'Ana',
            'apellidos' => 'García',
            'cui'       => '9876543210101',
        ]);

        $estudiante = Estudiante::where('cui', '9876543210101')->first();
        $this->assertNotNull($estudiante->id_usuario, 'Debe haberse creado una cuenta de usuario');
        $this->assertDatabaseHas('users', ['rol' => 'estudiante', 'email' => 'ana.garcia@eorm.local']);
    }

    public function test_duplicate_cui_is_rejected(): void
    {
        $admin = $this->createAdmin();
        [, $existing] = $this->createStudent();
        $existing->update(['cui' => '1111111111111']);

        $this->actingAs($admin)
             ->post(self::STORE_URL, [
                 'nombre'    => 'Otro',
                 'apellidos' => 'Alumno',
                 'cui'       => '1111111111111',
             ])
             ->assertSessionHasErrors('cui');
    }

    public function test_admin_can_update_estudiante(): void
    {
        $admin = $this->createAdmin();
        [, $estudiante] = $this->createStudent();
        $estudiante->update(['cui' => '2222222222222']);

        $this->actingAs($admin)
             ->put("/admin/estudiantes/{$estudiante->id}", [
                 'nombre'    => 'NuevoNombre',
                 'apellidos' => 'López',
                 'cui'       => '2222222222222',
             ])
             ->assertRedirect(route('admin.estudiantes.index'));

        $this->assertDatabaseHas('estudiantes', ['id' => $estudiante->id, 'nombre' => 'NuevoNombre']);
    }

    public function test_admin_can_delete_estudiante(): void
    {
        $admin = $this->createAdmin();
        [, $estudiante] = $this->createStudent();
        $gs = $this->createGradoSeccion();
        $this->createInscripcion($estudiante->id, $gs->id);

        $this->actingAs($admin)
             ->delete("/admin/estudiantes/{$estudiante->id}")
             ->assertRedirect(route('admin.estudiantes.index'));

        $this->assertDatabaseMissing('estudiantes',    ['id' => $estudiante->id]);
        $this->assertDatabaseMissing('inscripciones',  ['id_estudiante' => $estudiante->id]);
    }

    public function test_delete_estudiante_removes_all_related_records(): void
    {
        $admin = $this->createAdmin();
        [$teacherUser, $profesor] = $this->createTeacher();
        $gs      = $this->createGradoSeccion();
        $materia = $this->createMateria($gs->id);
        $materia->profesores()->attach($profesor->id);
        $periodo = $this->createPeriodo();
        $tarea   = $this->createTarea($materia->id, $periodo->id);
        [, $estudiante] = $this->createStudent();
        $this->createInscripcion($estudiante->id, $gs->id);

        // Simular una calificación guardada
        \App\Models\TareaEstudiante::create([
            'id_estudiante' => $estudiante->id,
            'id_tarea'      => $tarea->id,
            'calificacion'  => 90,
            'calificado'    => 1,
            'entrego'       => 1,
        ]);

        $this->actingAs($admin)
             ->delete("/admin/estudiantes/{$estudiante->id}")
             ->assertRedirect(route('admin.estudiantes.index'));

        $this->assertDatabaseMissing('estudiantes',     ['id' => $estudiante->id]);
        $this->assertDatabaseMissing('inscripciones',   ['id_estudiante' => $estudiante->id]);
        $this->assertDatabaseMissing('tarea_estudiante',['id_estudiante' => $estudiante->id]);
    }

    public function test_store_requires_nombre_apellidos_cui(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
             ->post(self::STORE_URL, [])
             ->assertSessionHasErrors(['nombre', 'apellidos', 'cui']);
    }
}
