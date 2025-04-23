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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nombreCompleto')->after('id')->nullable();
            $table->string('telefono')->nullable()->after('email');
            $table->enum('rol', ['admin', 'agente', 'conductor', 'cliente'])->after('telefono');
            $table->boolean('estado')->default(true)->after('rol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nombreCompleto', 'telefono', 'rol', 'estado']);
        });
    }
};
