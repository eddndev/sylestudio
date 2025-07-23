<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdminMiddleware
{
    /**
     * Verifica sesión + gate 'admin-access'.
     *  – Si no hay sesión → login.
     *  – Si no es admin     → home.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // Usa el gate definido en AuthServiceProvider
        if (! Gate::allows('admin-access')) {
            return redirect()->route('home')
                     ->with('error', 'No tienes autorización para entrar en el panel.');
        }

        return $next($request);
    }
}
