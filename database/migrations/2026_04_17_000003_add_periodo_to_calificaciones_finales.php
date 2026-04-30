<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeriodoToCalificacionesFinales extends Migration
{
    public function up()
    {
        Schema::table('calificaciones_finales', function (Blueprint $table) {
            $table->foreignId('id_periodo')->nullable()->after('id_materia')->constrained('periodos');
        });
    }

    public function down()
    {
        Schema::table('calificaciones_finales', function (Blueprint $table) {
            $table->dropForeign(['id_periodo']);
            $table->dropColumn('id_periodo');
        });
    }
}
