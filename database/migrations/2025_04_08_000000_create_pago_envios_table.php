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

            // Enlace con el envío
            $table->foreignId('envio_id')->constrained('envios')->onDelete('cascade');

            // Monto del pago
            $table->decimal('monto', 10, 2)->default(0);

            // Estado del pago
            $table->enum('estado', ['pendiente', 'completado', 'cancelado'])->default('pendiente');

            // Forma y medio de pago
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'deposito', 'billetera_digital', 'otros'])->default('efectivo');
            $table->enum('medio_pago', ['bcp', 'scotiabank', 'interbank', 'bbva', 'banbif', 'yape', 'plin',  'tunki', 'agora', 'otros'])->nullable();

            // Transacción
            $table->string('numero_transaccion')->nullable();
            $table->dateTime('fecha_pago')->nullable();

            // Cliente que hizo el pago
            $table->foreignId('realizado_por')->nullable()->constrained('clientes')->nullOnDelete();

            // Usuario del sistema que registró el pago
            $table->foreignId('cobrado_por')->nullable()->constrained('users')->nullOnDelete();

            // Agencia donde se realizó el pago
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
