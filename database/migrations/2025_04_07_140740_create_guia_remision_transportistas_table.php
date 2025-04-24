<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guia_remision_transportistas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('envio_id')->unique()->constrained('envios')->onDelete('cascade');
            $table->foreignId('viaje_id')->nullable()->constrained('viajes')->nullOnDelete();

            $table->string('codigo')->unique();
            $table->string('serie')->nullable();
            $table->integer('numero')->nullable();

            $table->dateTime('fecha_emision')->default(now());
            $table->date('fecha_inicio_traslado')->default(now());

            $table->enum('estado', [
                'borrador',
                'generada',
                'asignada',
                'impresa',
                'finalizada',
                'anulada'
            ])->default('generada');

            $table->enum('estado_sunat', ['pendiente', 'aceptado', 'rechazado', 'observado'])->default('pendiente');
            $table->string('codigo_sunat')->nullable();
            $table->string('descripcion_sunat')->nullable();


            // Archivos PDF generados
            $table->string('pdf_path_a4')->nullable();
            $table->string('pdf_path_ticket_80')->nullable();
            $table->string('pdf_path_ticket_58')->nullable();

            // Archivos SUNAT
            $table->string('xml_path')->nullable();
            $table->string('cdr_path')->nullable();
            $table->string('hash_code')->nullable();
            $table->string('ticket')->nullable();

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guia_remision_transportista');
    }
};
