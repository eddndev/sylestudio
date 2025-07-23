<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_assets', function (Blueprint $table) {
            $table->id();
            // Este campo es clave: almacenará la ruta original, ej: 'resources/images/hero.jpg'
            $table->string('original_path')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_assets');
    }
};
