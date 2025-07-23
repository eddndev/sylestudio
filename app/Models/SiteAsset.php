<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SiteAsset extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Registra las conversiones de imagen para este modelo.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        // Conversiones para AVIF
        $this->addMediaConversion('w-800-avif')->width(800)->format('avif')->nonQueued();
        $this->addMediaConversion('w-1600-avif')->width(1600)->format('avif')->nonQueued();

        // Conversiones para WEBP
        $this->addMediaConversion('w-800-webp')->width(800)->format('webp')->nonQueued();
        $this->addMediaConversion('w-1600-webp')->width(1600)->format('webp')->nonQueued();

        // Conversión de fallback en JPG
        $this->addMediaConversion('fallback-jpg')->width(1600)->format('jpg')->nonQueued();
    }
}