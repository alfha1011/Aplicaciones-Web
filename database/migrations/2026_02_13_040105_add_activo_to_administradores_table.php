<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('administradores', function (Blueprint $table) {
        $table->boolean('activo')->default(1)->after('password');
    });
}

public function down(): void
{
    Schema::table('administradores', function (Blueprint $table) {
        $table->dropColumn('activo');
    });
}

};
