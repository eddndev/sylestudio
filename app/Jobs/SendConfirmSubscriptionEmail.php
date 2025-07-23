<?php

namespace App\Jobs;

use App\Mail\ConfirmSubscriptionMail;
use App\Models\NewsletterSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendConfirmSubscriptionEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public NewsletterSubscription $sub) {}

    public function handle(): void
    {
        Log::info('SendConfirmSubscriptionEmail: start', [
            'sub_id' => $this->sub->id,
            'email'  => $this->sub->email,
        ]);

        try {
            Mail::to($this->sub->email)->send(new ConfirmSubscriptionMail($this->sub));

            Log::info('SendConfirmSubscriptionEmail: mail sent', [
                'sub_id' => $this->sub->id,
            ]);
        } catch (Throwable $e) {
            Log::error('SendConfirmSubscriptionEmail: exception', [
                'sub_id'  => $this->sub->id,
                'email'   => $this->sub->email,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            // Re-lanza para que la cola lo marque como failed y se ejecute failed()
            throw $e;
        }
    }

    /**
     * Se ejecuta cuando el Job falla definitivamente.
     */
    public function failed(Throwable $e): void
    {
        Log::critical('SendConfirmSubscriptionEmail: job FAILED', [
            'sub_id'  => $this->sub->id ?? null,
            'email'   => $this->sub->email ?? null,
            'message' => $e->getMessage(),
        ]);
    }
}
