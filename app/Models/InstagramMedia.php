<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class InstagramMedia extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'instagram_media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'url',
        'order_column',
        'is_visible',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_visible' => 'boolean',
    ];

    /**
     * Registra las conversiones de imagen para la publicación.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        // Conversión para mostrar en el frontend (formato WebP)
        $this->addMediaConversion('display-webp')
              ->width(800)
              ->format('webp')
              ->nonQueued(); // Procesamiento síncrono para simplicidad

        // Conversión para mostrar en el frontend (formato AVIF)
        $this->addMediaConversion('display-avif')
              ->width(800)
              ->format('avif')
              ->nonQueued();
    }
}
