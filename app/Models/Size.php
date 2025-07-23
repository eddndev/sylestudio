<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    public $timestamps = false; // catálogo estático

    /** @var array<int, string> */
    protected $fillable = ['code', 'label'];

    // ──────────────────────────────
    // Relaciones
    // ──────────────────────────────

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
