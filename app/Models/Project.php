<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

class Project extends Model implements HasMedia, Sitemapable
{
    use HasFactory, InteractsWithMedia;

    /**
     * CORRECCIÓN: Se añaden los campos al array $fillable para permitir la asignación masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function toSitemapTag(): Url | string | array
    {
        return Url::create(route('projects.show', $this))
            ->setLastModificationDate($this->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.8);
    }

    /**
     * Registra las conversiones de imagen para la galería del proyecto.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        // Miniatura para el panel de administración (rápida y ligera)
        $this->addMediaConversion('admin-thumb')
              ->width(200)
              ->height(200)
              ->sharpen(10)
              ->nonQueued();

        // Conversiones para la galería pública en formato WEBP
        $this->addMediaConversion('gallery-sm-webp')->width(400)->format('webp')->nonQueued();
        $this->addMediaConversion('gallery-md-webp')->width(800)->format('webp')->nonQueued();
        $this->addMediaConversion('gallery-lg-webp')->width(1200)->format('webp')->nonQueued();
        $this->addMediaConversion('gallery-xl-webp')->width(1920)->format('webp')->nonQueued();

        // Conversiones para la galería pública en formato AVIF (aún más optimizado)
        $this->addMediaConversion('gallery-sm-avif')->width(400)->format('avif')->nonQueued();
        $this->addMediaConversion('gallery-md-avif')->width(800)->format('avif')->nonQueued();
        $this->addMediaConversion('gallery-lg-avif')->width(1200)->format('avif')->nonQueued();
        $this->addMediaConversion('gallery-xl-avif')->width(1920)->format('avif')->nonQueued();
    }
}
