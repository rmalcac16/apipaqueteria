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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();

            $table->enum('tipo', ['tractocamion', 'remolque', 'semirremolque']);
            $table->string('placa')->unique();
            $table->string('tuc')->unique();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->integer('anio')->nullable();
            $table->decimal('capacidadKg', 8, 2)->nullable();
            $table->decimal('volumenM3', 8, 2)->nullable();
            $table->boolean('estado')->default(true);

            $table->foreignId('acopladoA_id')
                ->nullable()
                ->constrained('vehiculos')
                ->nullOnDelete()
                ->comment('Vehículo al que está acoplado (remolque o semirremolque)');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
