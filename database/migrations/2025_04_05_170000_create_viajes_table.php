<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('viajes', function (Blueprint $table) {
            $table->id();

            $table->string('codigo')->unique();

            $table->foreignId('user_id') // quien registró
                ->constrained('users')
                ->onDelete('restrict');

            // Vehículo principal (tractocamión)
            $table->foreignId('vehiculo_principal_id')
                ->constrained('vehiculos')
                ->onDelete('restrict');

            // Vehículo secundario (remolque/semirremolque), opcional
            $table->foreignId('vehiculo_secundario_id')
                ->nullable()
                ->constrained('vehiculos')
                ->onDelete('set null');

            // Conductor principal (obligatorio)
            $table->foreignId('conductor_principal_id')
                ->constrained('users')
                ->onDelete('restrict');

            // Conductor secundario (opcional)
            $table->foreignId('conductor_secundario_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Agencias
            $table->foreignId('agencia_origen_id')
                ->constrained('agencias')
                ->onDelete('restrict');

            $table->foreignId('agencia_destino_id')
                ->constrained('agencias')
                ->onDelete('restrict');

            $table->dateTime('fecha_salida');

            $table->dateTime('fecha_llegada')->nullable();

            $table->enum('estado', ['programado', 'en_transito', 'finalizado', 'cancelado'])
                ->default('programado');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};
