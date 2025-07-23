<?php
// database/migrations/2025_06_22_230500_add_nested_set_columns_to_categories.php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (! Schema::hasColumn('categories', '_lft')) {
                $table->integer('_lft')->unsigned()->default(0);
            }
            if (! Schema::hasColumn('categories', '_rgt')) {
                $table->integer('_rgt')->unsigned()->default(0);
            }
            // parent_id ya existe; si no, añádelo aquí
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['_lft','_rgt']);
        });
    }
};
