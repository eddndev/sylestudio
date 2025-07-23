<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsletterSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Evita duplicados incluso con SoftDeletes
            'email' => 'required|email:rfc,dns|unique:newsletter_subscriptions,email,NULL,id,deleted_at,NULL',
        ];
    }
}
