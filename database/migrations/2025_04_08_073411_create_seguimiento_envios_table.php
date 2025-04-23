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
        Schema::create('seguimiento_envios', function (Blueprint $table) {
            $table->foreignId('envio_id')->constrained('envios')->onDelete('cascade');
            $table->enum('estado', ['registrado', 'en_recojo', 'origen', 'asignado', 'desasignado', 'en_ruta', 'destino', 'entregado', 'cancelado']);
            $table->text('descripcion')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimiento_envios');
    }
};
