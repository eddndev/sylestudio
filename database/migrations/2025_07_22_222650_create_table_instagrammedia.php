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
        Schema::create('instagram_media', function (Blueprint $table) {
            $table->id();

            // Un título interno para identificar la publicación en el panel de administración.
            $table->string('title');

            // El enlace directo a la publicación original en Instagram.
            $table->string('url')->comment('Link to the Instagram post');

            // Para controlar el orden de aparición en la página.
            $table->unsignedInteger('order_column')->nullable();

            // Un booleano para activar o desactivar la visibilidad en el frontend.
            $table->boolean('is_visible')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_media');
    }
};
