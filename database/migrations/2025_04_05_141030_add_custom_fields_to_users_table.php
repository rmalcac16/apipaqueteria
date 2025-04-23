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
            $table->foreignId('agencia_id')->after('id')->nullable()->constrained('agencias')->nullOnDelete();
            $table->string('nombreCompleto')->after('agencia_id')->nullable();
            $table->string('numeroDocumento')->after('nombreCompleto')->unique();
            $table->string('numeroLicencia')->nullable()->after('numeroDocumento');
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
            $table->dropForeign(['agencia_id']);
            $table->dropColumn([
                'agencia_id',
                'nombreCompleto',
                'numeroDocumento',
                'numeroLicencia',
                'telefono',
                'rol',
                'estado',
            ]);
        });
    }
};
