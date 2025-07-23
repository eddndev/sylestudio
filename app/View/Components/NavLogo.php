<?php

namespace App\View\Components;

use Illuminate\View\Component;
use function logo_src_set;

class NavLogo extends Component
{
    // Hacemos públicas las propiedades para que sean accesibles desde la vista Blade
    public string $href;
    public readonly array $white;
    public readonly array $black;

    /**
     * Crea una nueva instancia del componente.
     *
     * @param string|null $href La URL a la que enlazará el logo. Por defecto, a la ruta 'home'.
     */
    public function __construct(?string $href = null)
    {
        // Si no se proporciona un href, se usa la ruta 'home' como predeterminado.
        // Esto mantiene la compatibilidad con el uso que ya le das en el sitio público.
        $this->href = $href ?? route('home');

        // La lógica para generar los srcsets permanece igual.
        // Usar `readonly` es una buena práctica de PHP 8+ para indicar que no cambiarán.
        $this->white = logo_src_set('Logo_SyleStudio_Syle_W');
        $this->black = logo_src_set('Logo_SyleStudio_Syle_B');
    }

    public function render()
    {
        return view('components.nav-logo');
    }
}