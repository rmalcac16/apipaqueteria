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
            $table->string('tipo_documento', 1)
                ->nullable()
                ->after('email')
                ->comment('Valores: 0-6, A-F');

            $table->string('numero_documento', 20)
                ->nullable()
                ->unique()
                ->after('tipo_documento')
                ->comment('Número de documento del usuario');

            $table->string('licencia', 20)
                ->nullable()
                ->after('numero_documento')
                ->comment('Número de licencia del conductor (si aplica)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tipo_documento', 'numero_documento', 'licencia']);
        });
    }
};
