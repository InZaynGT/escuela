<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMateriaProfesor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materia_profesor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_PROFESOR');
            $table->unsignedBigInteger('ID_MATERIA');
            $table->timestamps();

            $table->foreign('ID_PROFESOR')->references('id')->on('profesores');
            $table->foreign('ID_MATERIA')->references('id')->on('materia');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materia_profesor');
    }
}
