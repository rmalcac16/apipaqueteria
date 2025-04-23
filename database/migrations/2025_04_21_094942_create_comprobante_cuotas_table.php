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
        Schema::create('comprobante_cuotas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('comprobanteId')->constrained('comprobantes')->onDelete('cascade');

            $table->integer('numeroCuota')->default(1);
            $table->decimal('monto', 10, 2);
            $table->date('fechaVencimiento');
            $table->boolean('pagado')->default(false);
            $table->date('fechaPago')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobante_cuotas');
    }
};
