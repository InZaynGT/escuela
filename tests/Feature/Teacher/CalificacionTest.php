<?php

namespace Tests\Feature\Teacher;

use App\Models\TareaEstudiante;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Support\CreatesTestEntities;
use Tests\TestCase;

class CalificacionTest extends TestCase
{
    use RefreshDatabase, CreatesTestEntities;

    private function buildScenario(): array
    {
        [$teacherUser, $profesor] = $this->createTeacher();
        $gs      = $this->createGradoSeccion();
        $materia = $this->createMateria($gs->id);
        $materia->profesores()->attach($profesor->id);
        $periodo = $this->createPeriodo();
        $tarea   = $this->createTarea($materia->id, $periodo->id);
        [, $estudiante] = $this->createStudent();
        $this->createInscripcion($estudiante->id, $gs->id);

        return compact('teacherUser', 'materia', 'periodo', 'tarea', 'estudiante');
    }

    public function test_teacher_can_save_grade_for_own_student(): void
    {
        $s = $this->buildScenario();

        $this->actingAs($s['teacherUser'])
             ->post("/teacher/materias/{$s['materia']->id}/calificaciones", [
                 'id_periodo' => $s['periodo']->id,
                 'notas' => [
                     $s['estudiante']->id => [
                         $s['tarea']->id => ['calificacion' => '85', 'entrego' => '1'],
                     ],
                 ],
             ])
             ->assertRedirect();

        $this->assertDatabaseHas('tarea_estudiante', [
            'id_estudiante' => $s['estudiante']->id,
            'id_tarea'      => $s['tarea']->id,
            'calificacion'  => 85,
        ]);
    }

    public function test_teacher_cannot_save_grade_for_student_outside_their_group(): void
    {
        $s = $this->buildScenario();

        // Student enrolled in a different grado-seccion, not the teacher's
        $otherGs = $this->createGradoSeccion('Segundo Primaria', 'B');
        [, $foreignEstudiante] = $this->createStudent('otro@test.local');
        $this->createInscripcion($foreignEstudiante->id, $otherGs->id);

        $this->actingAs($s['teacherUser'])
             ->post("/teacher/materias/{$s['materia']->id}/calificaciones", [
                 'id_periodo' => $s['periodo']->id,
                 'notas' => [
                     $foreignEstudiante->id => [
                         $s['tarea']->id => ['calificacion' => '99', 'entrego' => '1'],
                     ],
                 ],
             ])
             ->assertRedirect();

        $this->assertDatabaseMissing('tarea_estudiante', [
            'id_estudiante' => $foreignEstudiante->id,
        ]);
    }

    public function test_teacher_cannot_save_grade_using_tarea_from_another_materia(): void
    {
        $s = $this->buildScenario();

        // Tarea that belongs to a completely different materia
        $otherGs      = $this->createGradoSeccion('Segundo Primaria', 'B');
        $otherMateria = $this->createMateria($otherGs->id, 'Ciencias');
        $foreignTarea = $this->createTarea($otherMateria->id, $s['periodo']->id, 'Tarea Ajena');

        $this->actingAs($s['teacherUser'])
             ->post("/teacher/materias/{$s['materia']->id}/calificaciones", [
                 'id_periodo' => $s['periodo']->id,
                 'notas' => [
                     $s['estudiante']->id => [
                         $foreignTarea->id => ['calificacion' => '99', 'entrego' => '1'],
                     ],
                 ],
             ])
             ->assertRedirect();

        $this->assertDatabaseMissing('tarea_estudiante', [
            'id_tarea' => $foreignTarea->id,
        ]);
    }

    public function test_teacher_without_access_to_materia_gets_403(): void
    {
        [$teacherUser] = $this->createTeacher();
        $gs      = $this->createGradoSeccion();
        $materia = $this->createMateria($gs->id); // NOT assigned to this teacher

        $this->actingAs($teacherUser)
             ->post("/teacher/materias/{$materia->id}/calificaciones", ['notas' => []])
             ->assertForbidden();
    }
}
