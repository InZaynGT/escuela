<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuditColumnsToAllTables extends Migration
{
    // Tablas que reciben created_by / updated_by
    private array $tables = [
        'grados',
        'secciones',
        'estudiantes',
        'profesores',
        'grado_seccion',
        'materias',
        'periodos',
        'inscripciones',
        'tareas',
        'tarea_estudiante',
        'calificaciones_finales',
        'responsables',
    ];

    public function up()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) use ($table) {
                $t->unsignedBigInteger('created_by')->nullable()->after('updated_at');
                $t->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                $t->foreign('created_by', "fk_{$table}_created_by")
                    ->references('id')->on('users')->nullOnDelete();
                $t->foreign('updated_by', "fk_{$table}_updated_by")
                    ->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down()
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) use ($table) {
                $t->dropForeign("fk_{$table}_created_by");
                $t->dropForeign("fk_{$table}_updated_by");
                $t->dropColumn(['created_by', 'updated_by']);
            });
        }
    }
}
