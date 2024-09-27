<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareasEstudiante extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarea_estudiante', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_estudiante');
            $table->unsignedBigInteger('id_tarea');
            $table->float('calificacion');
            $table->text('observaciones')->nullable();
            $table->boolean('entrego');
            $table->timestamps();

            $table->foreign('id_estudiante')->references('id')->on('estudiantes');
            $table->foreign('id_tarea')->references('id')->on('tareas');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarea_estudiante');
    }
}
