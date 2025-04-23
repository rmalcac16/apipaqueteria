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
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pagoEnviosId')->constrained('pago_envios')->onDelete('cascade');

            $table->enum('tipoComprobante', ['01', '03', '07', '08']);
            $table->string('serie', 4);
            $table->string('correlativo', 8);

            $table->string('clienteTipoDocumento');
            $table->string('clienteNumeroDocumento');
            $table->string('clienteNombre');
            $table->string('clienteDireccion')->nullable();

            $table->string('detalleServicio');
            $table->decimal('valorUnitario', 10, 2);
            $table->decimal('igv', 10, 2);
            $table->decimal('precioUnitario', 10, 2);
            $table->decimal('montoTotal', 10, 2);
            $table->string('unidadMedida')->default('ZZ');
            $table->string('codigoProducto')->nullable();
            $table->string('tipoAfectacionIgv')->default('10');

            $table->dateTime('fechaEmision');
            $table->string('moneda')->default('PEN');

            $table->enum('tipoPago', ['contado', 'credito'])->default('contado');

            $table->enum('estado', ['emitido', 'pendiente', 'anulado'])->default('emitido');

            $table->string('pdfPathA4')->nullable();
            $table->string('pdfPathTicket80')->nullable();
            $table->string('pdfPathTicket58')->nullable();

            $table->enum('sunatEstado', ['pendiente', 'enviado', 'aceptado', 'rechazado', 'error'])->default('pendiente');
            $table->string('sunatCodigoError')->nullable();
            $table->text('sunatMensajeError')->nullable();
            $table->string('sunatCdrPath')->nullable();

            $table->string('xmlPath')->nullable();
            $table->string('xmlHash')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};
