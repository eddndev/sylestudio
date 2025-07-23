<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 2025_06_15_000004_create_sizes_table.php
return new class extends Migration {
    public function up(): void
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();   // XS, S, M, L, 30x32…
            $table->string('label');                // “Extra Small”
        });
    }
    public function down(): void { Schema::dropIfExists('sizes'); }
};
