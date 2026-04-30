<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToAsistencias extends Migration
{
    public function up()
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->foreignId('id_materia')->nullable()->after('id_estudiante')->constrained('materias');
            $table->string('observacion')->nullable()->after('estado');
        });
    }

    public function down()
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropForeign(['id_materia']);
            $table->dropColumn(['id_materia', 'observacion']);
        });
    }
}
