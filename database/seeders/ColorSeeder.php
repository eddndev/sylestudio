<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;
use Illuminate\Support\Str;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['Black',       '#000000'],
            ['White',       '#FFFFFF'],
            ['Navy',        '#001F54'],
            ['Red',         '#E10600'],
            ['Royal Blue',  '#4169E1'],
            ['Forest Green','#228B22'],
            ['Mustard',     '#FFDB58'],
            ['Beige',       '#F5F5DC'],
            ['Gray',        '#808080'],
            ['Brown',       '#8B4513'],
        ];

        foreach ($colors as [$name, $hex]) {
            Color::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name'=>$name,'hex'=>$hex]
            );
        }
    }
}
