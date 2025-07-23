<?php
/* app/Helpers/helpers.php */

if (! function_exists('logo_src_set')) {
    /**
     * Genera un array de 'srcset' para los logos usando el helper get_image.
     *
     * @param string $name El nombre base del archivo del logo (sin extensión).
     * @return array
     */
    function logo_src_set(string $name): array
    {
        $sizes = [120, 240];
        $formats = ['avif', 'webp'];
        
        // Construimos la ruta completa del recurso como la guardamos en la BD.
        $originalPath = "resources/images/{$name}.png";

        return collect($formats)->mapWithKeys(fn ($fmt) => [
            $fmt => collect($sizes)->map(
                fn ($w) => get_image($originalPath, ['w' => $w, 'format' => $fmt]) . " {$w}w"
            )->implode(', ')
        ])->all();
    }
}