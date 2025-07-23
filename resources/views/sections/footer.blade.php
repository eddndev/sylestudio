{{-- 4. Contacto & RRSS --}}
@php
    $socials = [
        [
            'net'   => 'facebook',
            'label' => 'Facebook',
            'url'   => 'https://www.facebook.com/sylestudio',
        ],
        [
            'net'   => 'instagram',
            'label' => 'Instagram',
            'url'   => 'https://www.instagram.com/syle_studio/',    
        ],
        [
            'net'   => 'tiktok',
            'label' => 'TikTok',
            'url'   => 'https://www.tiktok.com/@syle_studio?lang=es-419',  
        ],
    ];
@endphp

<footer class="bg-neutral-950 text-on-primary" role="contentinfo">
    <div class="mx-auto max-w-7xl px-6 py-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">

        {{-- 1. Logo + misión --}}
        <div class="space-y-4">
            <div class="flex items-center space-x-4">
                <span class="text-2xl font-semibold">{{ config('app.name') }}</span>
                <x-nav-logo class="h-6 md:h-8" href="{{ route('home') }}"/>
            </div>
            <p class="text-sm max-w-xs">
                Estudio creativo.<br>
                Donde las ideas encuentran su lugar en el mundo.
                Fundado en México.
            </p>
        </div>

        {{-- 2. Sitemap --}}
        <nav aria-label="Primary navigation">
            <h3 class="font-semibold mb-4 uppercase text-xs tracking-wider">Explora</h3>
            <ul class="space-y-2">
                <li><a href="{{ route('home')   }}"   class="footer-link">Inicio</a></li>
                <li><a href="{{ route('about')  }}"   class="footer-link">Nosotros</a></li>
                <li><a href="{{ route('projects.index') }}"   class="footer-link">Proyectos</a></li>
            </ul>
        </nav>

        {{-- 3. Newsletter --}}
        <div>
            <h3 class="font-semibold mb-4 uppercase text-xs tracking-wider">SE PARTE DE NOSOTROS</h3>
            <form action="{{ route('newsletter.store') }}" method="POST" class="space-y-4" aria-label="Newsletter">
                @csrf
                <x-form-input name="email" type="email" placeholder="Dirección de email" required onblack />
                <x-primary-button class="w-full sm:w-auto">Suscribete</x-primary-button>
            </form>
        </div>

        {{-- 4. Contacto & RRSS --}}
        <div class="space-y-4">
            <h3 class="font-semibold mb-4 uppercase text-xs tracking-wider">Contáctanos</h3>

            <ul class="text-sm space-y-2">
                <li>Email: <a href="mailto:contacto@sylestudio.com" class="footer-link">contacto@sylestudio.com</a></li>
            </ul>

            <div class="flex space-x-4 mt-6">
                @foreach ($socials as $s)
                <a href="{{ $s['url'] }}"
                    class="group p-2 hover:bg-white/10"
                    aria-label="{{ $s['label'] }}"
                    target="_blank" rel="noopener">
                    <svg class="h-5 w-5 fill-current transition-colors group-hover:text-white text-on-primary/70">
                        <use xlink:href="#icon-{{ $s['net'] }}"/>
                    </svg>
                    <span class="sr-only">{{ $s['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- línea inferior --}}
    <div class="border-t border-outline-variant py-6 text-xs text-center tracking-wide">
        © {{ now()->year }} Syle Studio — All rights reserved.
    </div>
</footer>