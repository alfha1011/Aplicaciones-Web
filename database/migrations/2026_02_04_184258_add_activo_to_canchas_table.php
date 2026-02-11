<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::table('canchas', function (Blueprint $table) {
            // Columna activo: 1 = habilitado, 0 = deshabilitado
            // Por defecto todas las canchas están habilitadas
            $table->boolean('activo')->default(1)->after('estado');
        });
    }

    /**
     * Revertir los cambios
     */
    public function down()
    {
        Schema::table('canchas', function (Blueprint $table) {
            $table->dropColumn('activo');
        });
    }
};