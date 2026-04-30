<?php

namespace Tests\Feature\Admin;

use App\Models\TareaEstudiante;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Support\CreatesTestEntities;
use Tests\TestCase;

class PeriodoTest extends TestCase
{
    use RefreshDatabase, CreatesTestEntities;

    public function test_period_lock_prevents_saving_grades_via_ajax(): void
    {
        [$teacherUser, $profesor] = $this->createTeacher();
        $gs      = $this->createGradoSeccion();
        $materia = $this->createMateria($gs->id);
        $materia->profesores()->attach($profesor->id);
        $periodo = $this->createPeriodo(['bloqueado' => true]);
        $tarea   = $this->createTarea($materia->id, $periodo->id);
        [, $estudiante] = $this->createStudent();
        $this->createInscripcion($estudiante->id, $gs->id);

        $this->actingAs($teacherUser)
             ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
             ->post("/teacher/materias/{$materia->id}/calificaciones", [
                 'id_periodo' => $periodo->id,
                 'notas' => [
                     $estudiante->id => [
                         $tarea->id => ['calificacion' => '75', 'entrego' => '1'],
                     ],
                 ],
             ])
             ->assertStatus(423)
             ->assertJsonFragment(['error' => "El período \"{$periodo->nombre}\" está bloqueado. No se pueden modificar calificaciones."]);

        $this->assertDatabaseMissing('tarea_estudiante', ['id_tarea' => $tarea->id]);
    }

    public function test_period_lock_prevents_saving_grades_via_form(): void
    {
        [$teacherUser, $profesor] = $this->createTeacher();
        $gs      = $this->createGradoSeccion();
        $materia = $this->createMateria($gs->id);
        $materia->profesores()->attach($profesor->id);
        $periodo = $this->createPeriodo(['bloqueado' => true]);
        $tarea   = $this->createTarea($materia->id, $periodo->id);
        [, $estudiante] = $this->createStudent();
        $this->createInscripcion($estudiante->id, $gs->id);

        $this->actingAs($teacherUser)
             ->post("/teacher/materias/{$materia->id}/calificaciones", [
                 'id_periodo' => $periodo->id,
                 'notas' => [
                     $estudiante->id => [
                         $tarea->id => ['calificacion' => '75', 'entrego' => '1'],
                     ],
                 ],
             ])
             ->assertRedirect()
             ->assertSessionHas('error');

        $this->assertDatabaseMissing('tarea_estudiante', ['id_tarea' => $tarea->id]);
    }

    public function test_unlocked_period_allows_saving_grades(): void
    {
        [$teacherUser, $profesor] = $this->createTeacher();
        $gs      = $this->createGradoSeccion();
        $materia = $this->createMateria($gs->id);
        $materia->profesores()->attach($profesor->id);
        $periodo = $this->createPeriodo(['bloqueado' => false]);
        $tarea   = $this->createTarea($materia->id, $periodo->id);
        [, $estudiante] = $this->createStudent();
        $this->createInscripcion($estudiante->id, $gs->id);

        $this->actingAs($teacherUser)
             ->post("/teacher/materias/{$materia->id}/calificaciones", [
                 'id_periodo' => $periodo->id,
                 'notas' => [
                     $estudiante->id => [
                         $tarea->id => ['calificacion' => '90', 'entrego' => '1'],
                     ],
                 ],
             ])
             ->assertRedirect();

        $this->assertDatabaseHas('tarea_estudiante', [
            'id_tarea'     => $tarea->id,
            'calificacion' => 90,
        ]);
    }
}
