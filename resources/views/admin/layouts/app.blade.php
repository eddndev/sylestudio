{{-- resources/views/admin/layouts/app.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="h-full bg-surface text-on-surface">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') | {{ config('app.name') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">

    <!-- Claro -->
    <link rel="icon" href="/favicon-dark.ico" media="(prefers-color-scheme: light)" sizes="any">

    <!-- Oscuro -->
    <link rel="icon" href="/favicon-light.ico"  media="(prefers-color-scheme: dark)"  sizes="any">
    <script>
        window.routes = {
            store   : @json(route('admin.categories.store')),
            reorder : @json(route('admin.categories.reorder')),
            update  : id => @js(url('/admin/categories')).concat('/', id),
            productStore : @json(route('admin.products.store')),
            productUpdate : id => @js(route('admin.products.update', ':id')).replace(':id', id),
            productEdit  : id => @js(url('/admin/products')).concat('/', id, '/edit')
        };
        window.csrf = @json(csrf_token());
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-surface-variant">
@include('components.svg-sprite')
{{-- 
    Contenedor principal con Alpine.js para gestionar el estado de la barra lateral.
    x-data define el estado inicial: la barra lateral está cerrada en móvil.
--}}
<div x-data="{ sidebarOpen: false }" class="relative h-full flex">

    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 z-20 lg:hidden" aria-hidden="true">
    </div>

    <aside
        class="fixed inset-y-0 left-0 z-30 w-64 shrink-0 bg-black text-white flex flex-col transition-transform duration-300 ease-in-out
               transform -translate-x-full lg:translate-x-0"
        :class="{'translate-x-0': sidebarOpen}">

        <div class="px-4 py-4">
            <x-nav-logo :href="route('admin.dashboard')" />
        </div>

        <nav class="flex-1 space-y-1 px-4">
            <a href="{{ route('admin.dashboard') }}"
               class="block py-2 px-3 hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10' : '' }}">
               Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}"
               class="block py-2 px-3 hover:bg-white/10 {{ request()->routeIs('admin.products.*') ? 'bg-white/10' : '' }}">
                Products
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="block py-2 px-3 hover:bg-white/10 {{ request()->routeIs('admin.categories.*') ? 'bg-white/10' : '' }}">
                Categories
            </a>
            <a href="{{ route('admin.projects.index') }}"
               class="block py-2 px-3 hover:bg-white/10 {{ request()->routeIs('admin.projects.*') ? 'bg-white/10' : '' }}">
                Proyectos
            </a>
            <a href="{{ route('admin.instagram.index') }}"
               class="block py-2 px-3 hover:bg-white/10 {{ request()->routeIs('admin.instagram.*') ? 'bg-white/10' : '' }}">
                Instagram
            </a>
        </nav>

        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                {{-- Placeholder para un avatar --}}
                <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 flex flex-col text-sm">
                    <span class="font-semibold">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="contents">
                        @csrf
                        <button class="text-left text-xs text-white/60 hover:text-white">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col lg:pl-64">

        {{-- HEADER CORREGIDO --}}
        <header class="flex items-center p-4 sticky top-0 z-10
                    border-b border-outline-variant bg-surface shadow-sm">
            
            {{-- El botón hamburguesa no cambia --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden mr-2 p-2 rounded-md text-on-surface hover:bg-surface-variant">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>

            {{-- El h1 no necesita cambios, pero ahora se posicionará correctamente --}}
            <h1 class="text-lg font-semibold">@yield('header', 'Dashboard')</h1>
            
        </header>

        {{-- El <main> no necesita cambios en el layout --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>