<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProfesoresNombreIdentificacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profesores', function (Blueprint $table) {
            
            // Agregar la columna apellidos después de la columna nombre
            $table->string('apellidos')->after('nombre');

            // Agregar la columna teléfono
            $table->string('telefono', 15)->nullable()->after('apellidos');            
            
            // Agregar la columna ID_USUARIO como unsignedBigInteger
            $table->unsignedBigInteger('ID_USUARIO')->nullable()->after('apellidos');
            
            // Definir la clave foránea a la tabla users
            $table->foreign('ID_USUARIO')->references('id')->on('users');

            $table->unique('ID_USUARIO');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profesores', function (Blueprint $table) {
            // Eliminar la clave foránea y las columnas
            $table->dropForeign(['ID_USUARIO']);
            $table->dropColumn('ID_USUARIO');
            $table->dropColumn('apellidos');
            $table->dropColumn('telefono');
        });
    }
}
