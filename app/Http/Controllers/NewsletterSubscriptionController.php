<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsletterSubscriptionRequest;
use App\Jobs\SendConfirmSubscriptionEmail;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class NewsletterSubscriptionController extends Controller
{
    /**
     * POST /newsletter
     */
    public function store(StoreNewsletterSubscriptionRequest $request)
    {
        $sub = NewsletterSubscription::create([
            'email'         => $request->email,
            'confirm_token' => Str::uuid()->toString(),
            'ip'            => $request->ip(),
            'user_agent'    => $request->userAgent(),
        ]);

        SendConfirmSubscriptionEmail::dispatch($sub);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Revisa tu correo para confirmar tu suscripción.'
            ], 201);
        }

        return redirect()
            ->route('newsletter.pending')
            ->with('status', 'Revisa tu correo para confirmar tu suscripción.');
    }

    /**
     * GET /newsletter/pending
     */
    public function pending()
    {
        return view('newsletter.pending');
    }

    /**
     * GET /newsletter/confirm/{token}
     */
    public function confirm(Request $request, string $token)
    {
        abort_unless($request->hasValidSignature(), 401);

        $sub = NewsletterSubscription::where('confirm_token', $token)->firstOrFail();

        // Si ya estaba confirmada, puedes redirigir a otra vista si quieres
        if ($sub->confirmed_at) {
            return view('newsletter.already_confirmed', ['sub' => $sub]);
        }

        $sub->confirm();

        return view('newsletter.confirmed', ['sub' => $sub]);
    }

    /**
     * GET /newsletter/unsubscribe/{token}
     */
    public function unsubscribe(Request $request, string $token)
    {
        abort_unless($request->hasValidSignature(), 401);

        $sub = NewsletterSubscription::where('confirm_token', $token)->first();

        if (! $sub) {
            return view('newsletter.already_unsubscribed');
        }

        $sub->unsubscribe();

        return view('newsletter.unsubscribed', ['sub' => $sub]);
    }
}
