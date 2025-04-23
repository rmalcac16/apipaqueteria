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

            // 📌 Código único del viaje
            $table->string('codigo')->unique();

            // 👤 Usuario que creó el viaje (solo admin)
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');

            // 🚛 Vehículo y conductor asignados
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('restrict');
            $table->foreignId('conductor_id')->constrained('users')->onDelete('restrict');

            // 🏢 Origen (entre agencias)
            $table->foreignId('agencia_origen_id')->constrained('agencias')->onDelete('restrict');

            // 🏢 Destino
            $table->foreignId('agencia_destino_id')->constrained('agencias')->onDelete('restrict');

            // 📦 Datos de carga
            $table->unsignedInteger('cantidad_envios')->default(0);
            $table->decimal('peso_total_estimado', 8, 2)->default(0);
            $table->decimal('volumen_total_estimado', 8, 2)->default(0);
            $table->string('tipo_carga')->nullable(); // documentos, carga general, etc.

            // 📋 Capacidad del vehículo usada
            $table->decimal('capacidad_usada', 5, 2)->default(0); // en %
            $table->decimal('capacidad_maxima_permitida', 8, 2)->nullable(); // snapshot del vehículo

            // 📍 Fechas y tiempos
            $table->dateTime('fecha_salida');
            $table->dateTime('fecha_llegada_estimada')->nullable();
            $table->dateTime('fecha_llegada_real')->nullable();
            $table->time('hora_salida_programada')->nullable();
            $table->time('hora_salida_real')->nullable();
            $table->integer('tiempo_estimado_viaje')->nullable(); // minutos

            // 🚦 Estado y trazabilidad
            $table->enum('estado', ['programado', 'en_transito', 'finalizado', 'cancelado'])->default('programado');
            $table->string('estado_legible')->nullable(); // ejemplo: "En tránsito - Salió a las 14:00"

            // 🔐 Control y firmas
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->text('firma_digital_conductor')->nullable();
            $table->text('firma_digital_agencia_destino')->nullable();

            // 📑 Manifiesto
            $table->boolean('manifiesto_generado')->default(false);
            $table->string('codigo_manifiesto')->nullable();

            // ⚠️ Urgencia y SUNAT
            $table->boolean('viaje_urgente')->default(false);
            $table->boolean('sincronizado_con_sunat')->default(false);
            $table->string('tipo_viaje')->nullable(); // normal, retorno, interno, etc.

            // 📝 Observaciones
            $table->text('observaciones')->nullable();
            $table->text('observaciones_salida')->nullable();
            $table->text('observaciones_llegada')->nullable();

            // 🕒 Timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};
