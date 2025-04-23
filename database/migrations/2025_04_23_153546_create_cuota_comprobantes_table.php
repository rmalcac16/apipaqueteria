<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cuotas_comprobante', function (Blueprint $table) {
            $table->id();

            $table->foreignId('comprobante_id')->constrained('comprobantes')->onDelete('cascade');
            $table->unsignedInteger('numero_cuota');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_vencimiento');
            $table->enum('estado', ['pendiente', 'pagado'])->default('pendiente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuotas_comprobante');
    }
};
