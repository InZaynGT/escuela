<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RedesignAsistenciasTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('asistencias');

        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_estudiante')->constrained('estudiantes')->cascadeOnDelete();
            $table->foreignId('id_grado_seccion')->constrained('grado_seccion');
            $table->foreignId('id_periodo')->nullable()->constrained('periodos')->nullOnDelete();
            $table->date('fecha');
            $table->string('estado'); // presente | ausente | tardanza | justificado
            $table->string('observacion')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();

            // Un registro de asistencia por estudiante por día
            $table->unique(['id_estudiante', 'fecha']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('asistencias');

        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_estudiante')->constrained('estudiantes');
            $table->foreignId('id_materia')->nullable()->constrained('materias');
            $table->date('fecha');
            $table->string('estado');
            $table->string('observacion')->nullable();
            $table->timestamps();
        });
    }
}
