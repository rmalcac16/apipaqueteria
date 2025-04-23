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
        Schema::create('guia_remision_transportistas', function (Blueprint $table) {
            $table->id();

            $table->string('numero_documento')->unique()->comment('Número de la guía de remisión');

            $table->foreignId('envio_id')->constrained()->onDelete('cascade');

            // Conductor (fijado)
            $table->string('conductor_nombre');
            $table->string('conductor_tipo_documento');
            $table->string('conductor_documento');
            $table->string('conductor_licencia');

            // Vehículo (fijado)
            $table->string('vehiculo_placa');
            $table->string('vehiculo_tuc')->nullable();
            $table->string('vehiculo_certificado')->nullable();

            // Traslado
            $table->dateTime('fecha_inicio_traslado');
            $table->string('modo_transporte')->default('01'); // Público

            $table->string('punto_partida_ubigeo');
            $table->string('punto_partida_direccion');
            $table->string('punto_llegada_ubigeo');
            $table->string('punto_llegada_direccion');

            // Datos del envío
            $table->string('descripcion_carga');
            $table->decimal('peso_total', 8, 2);
            $table->string('unidad_medida')->default('KGM'); // Unidad de medida: KGM, TNE, etc.

            $table->string('remitente_nombre');
            $table->string('remitente_documento');

            $table->string('destinatario_nombre');
            $table->string('destinatario_documento');

            // Pagador del servicio
            $table->enum('pagador_tipo', ['remitente', 'destinatario', 'tercero']);
            $table->string('pagador_tipo_documento')->nullable();
            $table->string('pagador_numero_documento')->nullable();
            $table->string('pagador_nombre_razon_social')->nullable();

            // Interno
            $table->enum('estado', ['pendiente', 'enviado', 'anulado'])->default('pendiente');
            $table->string('pdf_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guia_remision_transportistas');
    }
};
