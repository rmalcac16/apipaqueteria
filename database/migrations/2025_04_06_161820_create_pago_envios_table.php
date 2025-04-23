<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pago_envios', function (Blueprint $table) {
            $table->id();

            // Enlace al envío
            $table->foreignId('envio_id')->constrained('envios')->onDelete('cascade');

            // Monto a pagar (no debe superar el flete del envío)
            $table->decimal('monto', 10, 2)->default(0);

            // Estado del pago
            $table->enum('estado', ['pendiente', 'completado', 'cancelado'])->default('pendiente');

            // Método: cómo se pagó (efectivo, transferencia, etc.)
            $table->enum('forma_pago', ['efectivo', 'transferencia', 'deposito'])->default('efectivo');

            // Medio: a través de qué canal o app
            $table->enum('medio_pago', ['yape', 'plin', 'bcp', 'interbank', 'bbva', 'otros'])->nullable();

            // Transacción y fecha
            $table->string('numero_transaccion')->nullable();
            $table->dateTime('fecha_pago')->nullable();

            // Cliente que realizó el pago
            $table->foreignId('realizado_por')->nullable()->constrained('clientes')->onDelete('restrict');

            // Usuario del sistema que lo registró (cajero, agente, admin)
            $table->foreignId('cobrado_por')->nullable()->constrained('users')->nullOnDelete();

            // Agencia donde se hizo el pago
            $table->foreignId('agencia_id')->nullable()->constrained('agencias')->nullOnDelete();

            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago_envios');
    }
};
