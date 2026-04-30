<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Support\CreatesTestEntities;
use Tests\TestCase;

class ReporteAsistenciaTest extends TestCase
{
    use RefreshDatabase, CreatesTestEntities;

    public function test_asistencia_report_only_counts_records_for_selected_grado_seccion(): void
    {
        $admin = $this->createAdmin();
        $gs1   = $this->createGradoSeccion('Primero', 'A');
        $gs2   = $this->createGradoSeccion('Segundo', 'B');
        [, $estudiante] = $this->createStudent();
        $this->createInscripcion($estudiante->id, $gs1->id);

        // GS1: 2 presentes + 1 ausente = 3 registros
        $this->createAsistencia($estudiante->id, $gs1->id, '2026-01-10', 'presente');
        $this->createAsistencia($estudiante->id, $gs1->id, '2026-01-11', 'presente');
        $this->createAsistencia($estudiante->id, $gs1->id, '2026-01-12', 'ausente');

        // GS2 (año anterior, fechas distintas): 5 registros — NO deben aparecer en el reporte de GS1
        $this->createAsistencia($estudiante->id, $gs2->id, '2025-01-10', 'presente');
        $this->createAsistencia($estudiante->id, $gs2->id, '2025-01-11', 'presente');
        $this->createAsistencia($estudiante->id, $gs2->id, '2025-01-12', 'presente');
        $this->createAsistencia($estudiante->id, $gs2->id, '2025-01-13', 'ausente');
        $this->createAsistencia($estudiante->id, $gs2->id, '2025-01-14', 'ausente');

        $response = $this->actingAs($admin)
            ->get("/admin/reportes/asistencia?id_grado_seccion={$gs1->id}");

        $response->assertOk();

        $resumen = $response->viewData('resumen');
        $fila    = $resumen->first(fn($r) => $r['est']->id === $estudiante->id);

        $this->assertNotNull($fila, 'El estudiante debe aparecer en el resumen');
        $this->assertEquals(3, $fila['total'],     'Solo deben contarse los registros de GS1');
        $this->assertEquals(2, $fila['presentes'], 'Dos registros presentes en GS1');
        $this->assertEquals(1, $fila['ausentes'],  'Un registro ausente en GS1');
    }

    public function test_asistencia_report_requires_grado_seccion_param_to_show_data(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/reportes/asistencia');
        $response->assertOk();

        $resumen = $response->viewData('resumen');
        $this->assertTrue($resumen->isEmpty(), 'Sin parámetro no debe mostrar datos');
    }
}
