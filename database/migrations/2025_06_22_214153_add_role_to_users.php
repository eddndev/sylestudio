<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/2025_06_22_000001_add_role_to_users.php
return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ① Opción boolean
            $table->boolean('is_admin')->default(false)->after('password');

            // ② Opción enum (más explícita y future-proof)
            // $table->enum('role', ['user', 'admin'])->default('user')->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['is_admin'])); // o 'role'
    }
};
