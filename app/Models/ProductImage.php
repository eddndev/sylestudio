<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $appends = ['url'];

    public $timestamps = false;

    /** @var array<int, string> */
    protected $fillable = ['src', 'alt', 'position'];

    // ──────────────────────────────
    // Relaciones polimórficas
    // ──────────────────────────────

    public function imageable()
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->src);   // mismo helper en todos lados
    }
}
