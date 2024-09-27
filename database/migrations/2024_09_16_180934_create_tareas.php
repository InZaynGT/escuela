<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nivel');
            $table->string('titulo');
            $table->string('descripcion')->nullable();
            $table->float('ponderacion');
            $table->unsignedBigInteger('id_profesor');
            $table->timestamps();

            $table->foreign('nivel')->references('id')->on('grado_seccion');
            $table->foreign('id_profesor')->references('id')->on('profesores');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tareas');
    }
}
