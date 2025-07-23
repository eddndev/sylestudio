<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsletterSubscription extends Model
{
    use HasFactory, SoftDeletes, Prunable;

    protected $guarded = [];

    protected $casts = [
        'confirmed_at'    => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    /**
     * Registros a podar: no confirmados después de 1 mes.
     */
    public function prunable()
    {
        return static::whereNull('confirmed_at')
                     ->where('created_at', '<=', now()->subMonth());
    }

    /**
     * Confirmar suscripción.
     */
    public function confirm(): void
    {
        $this->forceFill([
            'confirmed_at'  => now(),
            'confirm_token' => null,
        ])->save();
    }

    /**
     * Dar de baja (opt-out) la suscripción.
     */
    public function unsubscribe(): void
    {
        $this->update(['unsubscribed_at' => now()]);
    }
}
