<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBloqueadoToPeriodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('periodos', function (Blueprint $table) {
            $table->boolean('bloqueado')->default(false)->after('fecha_fin');
        });
    }

    public function down()
    {
        Schema::table('periodos', function (Blueprint $table) {
            $table->dropColumn('bloqueado');
        });
    }
}
