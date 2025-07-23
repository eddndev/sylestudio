<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// 2025_06_15_000005_create_colors_table.php (opcional)
return new class extends Migration {
    public function up(): void
    {
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // “Black”
            $table->string('hex', 7)->nullable();   // #000000
            $table->string('slug')->unique();
        });
    }
    public function down(): void { Schema::dropIfExists('colors'); }
};
