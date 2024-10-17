<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMaterias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materia', function (Blueprint $table) {
            // Agregar columna 'ID_GRADO_SECCION' como unsignedBigInteger
            $table->unsignedBigInteger('ID_GRADO_SECCION')->nullable()->after('nombre'); // Asegúrate de ajustar la ubicación de la columna

            // Definir la clave foránea
            $table->foreign('ID_GRADO_SECCION')->references('id')->on('grado_seccion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materias', function (Blueprint $table) {
            // Eliminar la clave foránea y la columna
            $table->dropForeign(['ID_GRADO_SECCION']);
            $table->dropColumn('ID_GRADO_SECCION');
        });
    }
}
