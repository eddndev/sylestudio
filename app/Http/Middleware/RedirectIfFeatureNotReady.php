<?php
// PASO 1: Crea este nuevo archivo en:
// app/Http/Middleware/RedirectIfFeatureNotReady.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfFeatureNotReady
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Simplemente redirige cualquier petición que pase por este middleware
        // a la ruta con el nombre 'coming-soon'.
        return redirect()->route('coming-soon');
    }
}
