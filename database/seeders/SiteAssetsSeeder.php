<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\SiteAsset;

class SiteAssetsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['path' => 'resources/images/Logo_SyleStudio_Syle_B.png', 'collection' => 'default'],
            ['path' => 'resources/images/Logo_SyleStudio_Syle_W.png', 'collection' => 'default'],
            ['path' => 'resources/images/hero.jpg', 'collection' => 'default'],
            ['path' => 'resources/images/join.jpg', 'collection' => 'default'],
            ['path' => 'resources/images/about.jpg', 'collection' => 'default'],
            ['path' => 'resources/images/about-manifiesto-1.jpg', 'collection' => 'default'],
            ['path' => 'resources/images/about-manifiesto-2.jpg', 'collection' => 'default'],
            ['path' => 'resources/images/about-manifiesto-3.jpg', 'collection' => 'default'],
            ['path' => 'resources/images/coming1.jpg', 'collection' => 'default'],
            ['path' => 'resources/images/coming2.jpg', 'collection' => 'default']
        ];

        foreach ($items as $item) {
            $absPath = base_path($item['path']); // o resource_path('images/xxx.png')

            if (!File::exists($absPath)) {
                $this->command?->warn("⚠️ No existe: {$absPath}");
                continue;
            }

            $asset = SiteAsset::firstOrCreate(
                ['original_path' => $item['path']]
            );

            // Evita duplicar media si ya está cargado
            if ($asset->getMedia($item['collection'])->isNotEmpty()) {
                $this->command?->info("✓ Ya cargado: {$item['path']}, salto.");
                continue;
            }

            $asset->addMedia($absPath)
                  ->preservingOriginal()
                  ->toMediaCollection($item['collection']);

            $this->command?->info("+ Importado: {$item['path']}");
        }
    }
}
