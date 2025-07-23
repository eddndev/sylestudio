<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        // Desactiva claves foráneas para un seed limpio (MySQL / MariaDB)
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Catálogo base de tallas — ajusta o expande a tu gusto
        $sizes = [
            ['code' => 'XS',  'label' => 'Extra Small'],   // Extra Pequeño
            ['code' => 'S',   'label' => 'Small'],         // Pequeño
            ['code' => 'M',   'label' => 'Medium'],        // Mediano
            ['code' => 'L',   'label' => 'Large'],         // Grande
            ['code' => 'XL',  'label' => 'Extra Large'],   // Extra Grande
            ['code' => 'XXL', 'label' => '2X Large'],      // Doble Extra
        ];

        foreach ($sizes as $size) {
            // updateOrCreate evita duplicados si ya existen
            Size::updateOrCreate(
                ['code' => $size['code']],
                ['label' => $size['label']]
            );
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
