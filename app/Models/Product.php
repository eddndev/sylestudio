<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    /** @var array<int, string> */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'base_price',
        'status',
        'gender_hint',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'base_price' => 'decimal:2',
    ];

    // ──────────────────────────────
    // Relaciones
    // ──────────────────────────────

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
        
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->morphMany(ProductImage::class, 'imageable');
    }

    public function mainImage(): MorphOne
    {
        return $this->morphOne(ProductImage::class, 'imageable')
                    ->oldest('position');   // primer frame como “principal”
    }
    public function getMainImageUrlAttribute(): string
    {
        return $this->mainImage?->url
            ?? asset('img/placeholder.png'); // fallback
    }

}
