<?php

namespace App\Http\Controllers;

use App\Models\InstagramMedia;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Muestra la página "Sobre Nosotros" con las publicaciones de la comunidad.
     */
    public function about()
    {
        // Se obtienen las últimas 6 publicaciones marcadas como visibles,
        // ordenadas por la columna 'order_column' (si la usas) o por fecha de creación.
        // El 'with('media')' es crucial para evitar N+1 queries (Eager Loading).
        $instagramPosts = InstagramMedia::where('is_visible', true)
                                        ->with('media')
                                        ->latest() // O ->orderBy('order_column', 'asc')
                                        ->take(6)
                                        ->get();

        return view('pages.about-us', [
            'instagramPosts' => $instagramPosts,
        ]);
    }

    /**
     * ✅ NUEVO: Muestra la página "Próximamente".
     * Reutiliza la lógica para obtener las publicaciones de Instagram.
     */
    public function comingSoon()
    {
        $instagramPosts = InstagramMedia::where('is_visible', true)
                                        ->with('media')
                                        ->latest()
                                        ->take(6)
                                        ->get();

        return view('pages.coming-soon', [
            'instagramPosts' => $instagramPosts,
        ]);
    }
}