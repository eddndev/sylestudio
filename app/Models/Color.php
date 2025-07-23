<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    public $timestamps = false;

    /** @var array<int, string> */
    protected $fillable = ['name', 'hex', 'slug'];

    // ──────────────────────────────
    // Relaciones
    // ──────────────────────────────

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
