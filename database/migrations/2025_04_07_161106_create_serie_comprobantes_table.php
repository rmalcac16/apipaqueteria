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
        Schema::create('serie_comprobantes', function (Blueprint $table) {
            $table->id();

            $table->enum('tipo_comprobante', ['factura', 'boleta']);
            $table->string('serie', 4)->unique();
            $table->string('descripcion')->nullable();
            $table->enum('estado', ['activa', 'inactiva'])->default('activa');

            $table->string('sunat_origen')->nullable()->default('SEE del contribuyente')->comment('Origen del comprobante: SEE del contribuyente, SEE de la SUNAT, etc.');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serie_comprobantes');
    }
};
