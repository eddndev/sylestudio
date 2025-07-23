@php
    $links = [
        ['label' => 'INICIO',    'route' => route('home')],
        ['label' => 'NOSOTROS',  'route' => route('about')],
        // ✅ CORRECCIÓN: Se actualiza el nombre de la ruta de 'projects' a 'projects.index'
        // para coincidir con la nueva estructura de rutas agrupadas.
        ['label' => 'PROYECTOS', 'route' => route('projects.index')],
    ];
@endphp

<header x-data="navbar()" class="fixed inset-x-0 top-0 z-50">
    {{-- ───────────── NAV BAR ───────────── --}}
    <nav id="mainNav"
         class="flex h-16 items-center px-4 md:px-8 transition-colors duration-300
                bg-transparent text-on-primary">

        {{-- 1. Mobile search (hidden ≥ md) --}}
        <div class="flex md:hidden flex-1">
            <button aria-label="Search" class="mr-4">
                <svg class="h-6 w-6 stroke-current"><use xlink:href="#icon-search"/></svg>
            </button>
        </div>

        {{-- 2. Main links (desktop) --}}
        <div class="flex-1 hidden md:flex space-x-6">
            @foreach ($links as $link)
                <x-nav-link
                    :href="$link['route']"
                    :active="url()->current() === $link['route']">
                    {{ $link['label'] }}
                </x-nav-link>
            @endforeach
        </div>

        {{-- 3. Center logo --}}
        <x-nav-logo class="h-8 md:h-10"/>

        {{-- 4. Right-side actions --}}
        <div class="flex flex-1 justify-end items-center space-x-6">

            {{-- Desktop only --}}
            <div class="hidden md:flex items-center space-x-6">

                <div x-data="{ open: false }"
                     class="relative h-6 w-6 flex-shrink-0">

                    <button type="button"
                            class="icon-btn"
                            aria-label="Account"
                            @click="open = !open"
                            @keydown.escape.window="open = false">
                        <svg class="block h-full w-full stroke-current">
                            <use xlink:href="#icon-user"/>
                        </svg>
                    </button>

                    {{-- dropdown --}}
                    <div x-show="open"
                         x-transition.origin.top.right
                         x-cloak
                         @click.away="open = false"
                         class="absolute right-0 top-full mt-2 w-44 rounded bg-surface text-on-surface
                                shadow-lg ring-1 ring-outline/20 divide-y divide-outline/10">
                        @guest
                            <a href="{{ route('login') }}"
                               class="block px-4 py-3 text-sm hover:bg-surface-variant/60">
                                Iniciar sesión
                            </a>
                            <a href="{{ route('register') }}"
                               class="block px-4 py-3 text-sm hover:bg-surface-variant/60">
                                Crear cuenta
                            </a>
                        @else
                            {{-- Si el usuario es administrador, ruta /admin --}}
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}"
                                   class="block px-4 py-3 text-sm hover:bg-surface-variant/60">
                                   Panel de administración
                                </a>
                            @endif
                            {{--
                            <a href="{{ route('profile.edit') }}"
                               class="block px-4 py-3 text-sm hover:bg-surface-variant/60">
                                Mi perfil
                            </a>
                            --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-3 text-sm hover:bg-surface-variant/60">
                                    Cerrar sesión
                                </button>
                            </form>
                        @endguest
                    </div>
                </div>
                {{-- B. Search --}}
                <button aria-label="Search" class="icon-btn">
                    <svg class="h-5 w-5 stroke-current"><use xlink:href="#icon-search"/></svg>
                </button>

                {{-- C. Wishlist --}}
                {{--
                <a href="{{ route('wishlist') }}" aria-label="Wishlist" class="icon-btn">
                    <svg class="h-5 w-5 stroke-current"><use xlink:href="#icon-heart"/></svg>
                </a>
                --}}

                {{-- D. Cart --}}
                {{--
                <a href="{{ route('cart') }}" aria-label="Cart" class="icon-btn relative">
                    <svg class="h-5 w-5 stroke-current"><use xlink:href="#icon-bag"/></svg>
                    @if(session('cart_count', 0) > 0)
                        <span class="absolute -top-1 -right-2 rounded-full bg-primary text-on-primary
                                     text-[10px] px-1">
                            {{ session('cart_count') }}
                        </span>
                    @endif
                </a>
                --}}
            </div>

            {{-- Mobile icons --}}
            {{--
            <a href="{{ route('cart') }}" aria-label="Cart" class="md:hidden mr-4">
                <svg class="h-6 w-6 stroke-current"><use xlink:href="#icon-bag"/></svg>
            </a>
            --}}

            {{-- Hamburger menu (mobile) --}}
            <button @click="toggle" aria-label="Open menu" class="md:hidden">
                <svg class="h-6 w-6 stroke-current"><use xlink:href="#icon-menu"/></svg>
            </button>
        </div>
    </nav>

    {{-- ───────────── Drawer móvil ───────────── --}}
    <aside x-show="open"
           x-transition.opacity
           x-cloak
           class="fixed inset-0 z-40 flex flex-col bg-surface text-on-surface">
        {{-- Header --}}
        <div class="flex items-center justify-between h-16 px-4 border-b border-outline">
            <span class="text-lg font-bold">Menu</span>
            <button @click="toggle" aria-label="Close">
                <svg class="h-6 w-6 stroke-current"><use xlink:href="#icon-close"/></svg>
            </button>
        </div>

        {{-- Main links --}}
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            @foreach ($links as $link)
                <x-responsive-nav-link
                    :href="$link['route']"
                    :active="url()->current() === $link['route']">
                    {{ $link['label'] }}
                </x-responsive-nav-link>
            @endforeach
        </nav>

        {{-- Account / cart --}}
        <div class="p-4 border-t border-outline space-y-3">
            @guest
                <x-responsive-nav-link href="{{ route('login') }}">
                    <svg class="h-5 w-5 mr-2 inline stroke-current"><use xlink:href="#icon-user"/></svg>
                    Iniciar sesión
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('register') }}">
                    <svg class="h-5 w-5 mr-2 inline stroke-current"><use xlink:href="#icon-user-plus"/></svg>
                    Crear cuenta
                </x-responsive-nav-link>
            @else
                {{-- 
                <x-responsive-nav-link href="{{ route('profile.edit') }}">
                    <svg class="h-5 w-5 mr-2 inline stroke-current"><use xlink:href="#icon-user"/></svg>
                    Mi perfil
                </x-responsive-nav-link>
                --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link as="button" type="submit">
                        <svg class="h-5 w-5 mr-2 inline stroke-current"><use xlink:href="#icon-logout"/></svg>
                        Cerrar sesión
                    </x-responsive-nav-link>
                </form>
            @endguest
            {{--
            <x-responsive-nav-link href="{{ route('cart') }}">
                
                <svg class="h-5 w-5 mr-2 inline stroke-current"><use xlink:href="#icon-bag"/></svg>
                Mi carrito
                @if(session('cart_count', 0) > 0)
                    <span class="ml-auto rounded-full bg-primary text-on-primary text-[10px] px-1">
                        {{ session('cart_count') }}
                    </span>
                @endif
            </x-responsive-nav-link>
            --}}
        </div>
    </aside>

    {{-- Alpine inline --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('navbar', () => ({
                open: false,
                toggle() {
                    this.open = !this.open
                    window.dispatchEvent(new Event(this.open ? 'nav:open' : 'nav:close'))
                }
            }))
        })
    </script>
</header>
