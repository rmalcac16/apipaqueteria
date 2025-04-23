<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('envio_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('envio_id')->constrained('envios')->onDelete('cascade');

            $table->integer('cantidad');
            $table->enum('unidad_medida', ['NIU', 'KGM', 'TN', 'LBR', 'OZ', 'CJ', 'BL', 'UND'])->default('NIU');

            $table->string('codigo')->nullable();
            $table->string('descripcion', 500);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('envio_items');
    }
};
