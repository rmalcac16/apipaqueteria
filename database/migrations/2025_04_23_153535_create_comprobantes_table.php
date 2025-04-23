<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pago_envio_id')->constrained('pago_envios')->onDelete('cascade');

            $table->enum('tipo', ['01', '03']); // 01 = Factura, 03 = Boleta
            $table->string('serie', 4);
            $table->unsignedBigInteger('numero');

            $table->enum('forma_pago', ['contado', 'credito'])->default('contado');
            $table->decimal('monto_total', 10, 2);

            $table->enum('estado', ['generado', 'enviado', 'anulado'])->default('generado');
            $table->enum('estado_sunat', ['pendiente', 'aceptado', 'rechazado', 'observado'])->default('pendiente');

            $table->string('xml_path')->nullable();
            $table->string('cdr_path')->nullable();
            $table->string('pdf_path')->nullable();

            $table->dateTime('fecha_emision')->default(now());

            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};
