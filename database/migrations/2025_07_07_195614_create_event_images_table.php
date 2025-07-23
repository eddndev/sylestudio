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
        Schema::create('event_images', function (Blueprint $table) {
            $table->id(); // ID único para la imagen

            // Clave foránea para relacionar con la tabla 'events'
            $table->foreignId('event_id')
                  ->constrained('events') // Asegura que el event_id existe en la tabla events
                  ->onDelete('cascade'); // Si se elimina un evento, se eliminan sus imágenes asociadas

            $table->string('image_path'); // Ruta al archivo de la imagen
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_images');
    }
};
