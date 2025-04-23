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
        Schema::create('documento_sustento_guia_transportistas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('guia_remision_transportistas_id');

            $table->foreign('guia_remision_transportistas_id', 'fk_guia_transportista')
                ->references('id')
                ->on('guia_remision_transportistas')
                ->onDelete('cascade');

            $table->string('tipo_documento', 2);

            $table->string('serie_numero');

            $table->string('ruc_emisor', 11);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_sustento_guia_transportistas');
    }
};
