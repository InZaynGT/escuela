<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToResponsables extends Migration
{
    public function up()
    {
        Schema::table('responsables', function (Blueprint $table) {
            $table->string('apellidos')->nullable()->after('nombre');
            $table->string('parentesco')->nullable()->after('telefono');
        });
    }

    public function down()
    {
        Schema::table('responsables', function (Blueprint $table) {
            $table->dropColumn(['apellidos', 'parentesco']);
        });
    }
}
