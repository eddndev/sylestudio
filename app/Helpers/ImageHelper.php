<?php

use App\Models\SiteAsset;
use Illuminate\Support\Facades\Cache;

if (!function_exists('get_image')) {
    /**
     * Obtiene la URL de una imagen procesada por Spatie Media Library.
     * Esta función actúa como un facade para el sistema de Site Assets,
     * permitiendo una consulta eficiente y cacheada de las URLs de las conversiones.
     *
     * @param string $originalPath La ruta de recurso original, ej: 'resources/images/hero.jpg'
     * @param array $options Un array asociativo para especificar la conversión, ej: ['w' => 800, 'format' => 'avif']
     * @return string La URL completa del asset procesado o una URL de fallback.
     */
    function get_image(string $originalPath, array $options = []): string
    {
        // Generamos una clave de caché única y legible basada en el nombre del archivo.
        // Esto normaliza la clave y evita caracteres especiales de la ruta.
        $cacheKey = 'site_asset_' . basename($originalPath);

        // Utilizamos el caché "rememberForever" para minimizar los hits a la base de datos.
        // La consulta a la tabla `site_assets` solo se ejecutará una vez por cada imagen
        // durante el ciclo de vida de la aplicación, hasta que el caché sea purgado.
        $asset = Cache::rememberForever($cacheKey, function () use ($originalPath) {
            // Realizamos una consulta a la base de datos para encontrar el registro
            // que corresponde a la ruta original de la imagen.
            return SiteAsset::where('original_path', $originalPath)->first();
        });

        // Si el asset no existe en la base de datos, retornamos una URL de fallback.
        // Esto previene errores fatales en producción si una imagen es referenciada pero no ha sido registrada.
        if (!$asset) {
            // `asset()` es el helper de Laravel que apunta al directorio `public`.
            return asset('images/placeholder.jpg'); // Asegúrate de tener esta imagen de placeholder.
        }

        // Si no se especifican opciones, devolvemos la conversión de fallback por defecto.
        if (empty($options)) {
            return $asset->getFirstMediaUrl('default', 'fallback-jpg');
        }

        // Construimos dinámicamente el nombre de la conversión basado en las opciones.
        // Ej: ['w' => 800, 'format' => 'avif'] se convierte en 'w-800-avif'.
        // Usamos el operador de coalescencia nula (??) para establecer valores por defecto.
        $conversionName = 'w-' . ($options['w'] ?? '1600') . '-' . ($options['format'] ?? 'jpg');

        // Solicitamos a Spatie Media Library la URL de la conversión específica.
        // El primer parámetro 'default' es el nombre de la colección de medios.
        return $asset->getFirstMediaUrl('default', $conversionName);
    }
}