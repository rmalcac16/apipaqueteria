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
        Schema::create('envios', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('numeroOrden')->unique();
            $table->string('codigo')->unique();

            $table->dateTime('fechaEmision')->default(now());
            $table->date('fechaTraslado')->default(now());

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('remitente_id')
                ->nullable()
                ->constrained('clientes')
                ->nullOnDelete();

            $table->foreignId('destinatario_id')
                ->nullable()
                ->constrained('clientes')
                ->nullOnDelete();

            $table->foreignId('pagador_id')
                ->nullable()
                ->constrained('clientes')
                ->nullOnDelete();

            $table->foreignId('viaje_id')
                ->nullable()
                ->constrained('viajes')
                ->nullOnDelete();

            $table->foreignId('agencia_origen_id')
                ->constrained('agencias')
                ->onDelete('cascade');

            $table->boolean('recojoDomicilio')->default(false);
            $table->string('recojo_direccion')->nullable();
            $table->string('recojo_ubigeo', 6)->nullable();
            $table->string('recojo_distrito')->nullable();
            $table->string('recojo_provincia')->nullable();
            $table->string('recojo_departamento')->nullable();
            $table->string('recojo_referencia')->nullable();
            $table->string('recojo_telefono')->nullable();

            $table->foreignId('agencia_destino_id')
                ->constrained('agencias')
                ->onDelete('cascade');

            $table->boolean('entregaDomicilio')->default(false);
            $table->string('entrega_direccion')->nullable();
            $table->string('entrega_ubigeo', 6)->nullable();
            $table->string('entrega_distrito')->nullable();
            $table->string('entrega_provincia')->nullable();
            $table->string('entrega_departamento')->nullable();
            $table->string('entrega_referencia')->nullable();
            $table->string('entrega_telefono')->nullable();

            $table->decimal('pesoTotal', 8, 2)->nullable();
            $table->enum('unidadMedida', ['KGM', 'TN', 'LBR', 'OZ'])->default('KGM');
            $table->decimal('valorDeclarado', 8, 2)->nullable();
            $table->boolean('esFragil')->default(false);
            $table->boolean('esPeligroso')->default(false);
            $table->double('costoEnvio')->nullable();
            $table->enum('formaPago', ['contado', 'contraentrega', 'credito'])->default('contado');

            $table->text('observaciones')->nullable();

            $table->string('pdf_path_a4')->nullable();
            $table->string('pdf_path_ticket_80')->nullable();
            $table->string('pdf_path_ticket_58')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('envios');
    }
};
