<?php

namespace App\Mail;

use App\Models\NewsletterSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Facades\URL;

class ConfirmSubscriptionMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(public NewsletterSubscription $sub) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Confirma tu suscripción');
    }

    public function content(): Content
    {
        $url = URL::temporarySignedRoute('newsletter.confirm', now()->addHours(24), [
            'token' => $this->sub->confirm_token,
        ]);

        return new Content(
            markdown: 'mail.newsletter.confirm',
            with: ['url' => $url]
        );
    }
}
