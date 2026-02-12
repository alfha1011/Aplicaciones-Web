<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('canchas', function (Blueprint $table) {
            $table->string('imagen1')->nullable()->after('activo');
            $table->string('imagen2')->nullable()->after('imagen1');
            $table->string('imagen3')->nullable()->after('imagen2');
        });
    }

    public function down(): void
    {
        Schema::table('canchas', function (Blueprint $table) {
            $table->dropColumn(['imagen1', 'imagen2', 'imagen3']);
        });
    }
};