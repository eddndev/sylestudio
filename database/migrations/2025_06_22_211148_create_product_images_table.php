<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 2025_06_15_000007_create_product_images_table.php
return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->morphs('imageable');            // product OR product_variant
            $table->string('src');
            $table->string('alt')->nullable();
            $table->unsignedTinyInteger('position')->default(0);
        });
    }
    public function down(): void { Schema::dropIfExists('product_images'); }
};
