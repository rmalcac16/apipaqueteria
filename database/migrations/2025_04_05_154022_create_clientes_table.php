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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipoDocumento', ['0', '1', '4', '6', '7', 'A', 'B', 'C', 'D', 'E', 'F'])->default('1');
            $table->string('numeroDocumento')->unique();
            $table->string('nombreCompleto');
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->boolean('frecuente')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
