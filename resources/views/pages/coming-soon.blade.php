{{-- 
    =================================================================
    ARCHIVO: resources/views/pages/coming-soon.blade.php
    =================================================================
--}}
@extends('layouts.app')

@section('title', 'Próximamente')

@section('content')
{{-- HERO ------------------------------------------------------------------}}
<section class="relative h-screen overflow-hidden" id="hero">
    
    {{-- Se reutiliza el componente de imagen para el fondo del hero.
         Asegúrate de tener un asset con la clave 'coming-soon-hero.jpg'. --}}
    <x-responsive-image 
        key="hero.jpg" 
        alt="Syle Studio - Próximamente"
        class="absolute inset-0 w-full h-full"
        loading="eager"
    />

    {{-- Overlay + contenido --}}
    <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6
                bg-gradient-to-b from-black/70 via-black/60 to-black/70">

        <h1 class="font-teko text-4xl sm:text-6xl md:text-8xl font-bold tracking-wider text-white uppercase">
            Estamos trabajando en este sitio
        </h1>
        <p class="text-xl md:text-2xl font-light tracking-widest text-white/90 mt-2">
            Nuevas experiencias llegarán pronto.
        </p>
    </div>
</section>

{{-- CONTENIDO ------------------------------------------------------------- --}}
<main class="bg-surface">

    {{-- SECCIÓN DE PUBLICACIONES DE LA COMUNIDAD --}}
    <section class="text-center bg-surface-variant">
        @if (isset($instagramPosts) && $instagramPosts->isNotEmpty())
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6 gap-0">
                @foreach ($instagramPosts as $post)
                    {{-- Lógica de visibilidad corregida para la grilla responsiva --}}
                    <div class="@if($loop->index >= 4) hidden xl:block @endif @if($loop->index >= 4 && $loop->index < 6) 2xl:block @endif">
                        <x-instagram-post-card :post="$post" />
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-on-surface/70">Aún no hay publicaciones para mostrar. ¡Síguenos en Instagram!</p>
        @endif
    </section>

    {{-- SECCIÓN "JOIN OUR CLUB" --}}
    <section id="join" class="relative">
        {{-- Imagen de fondo --}}
        <div class="absolute inset-0">
            <x-responsive-image
                key="join.jpg"
                alt="Community gathering by the sea"
                class="h-full w-full object-cover"
                loading="lazy"
            />
        </div>

        {{-- Overlay oscuro para legibilidad --}}
        <div class="absolute inset-0 bg-black/60 mix-blend-multiply"></div>

        {{-- Contenido sobre la imagen --}}
        <div class="relative grid grid-cols-1 md:grid-cols-3">
            <div class="md:col-span-2 flex flex-col justify-center px-8 py-16 md:px-20 text-white space-y-6">
                <h2 class="font-teko text-4xl md:text-6xl font-bold uppercase leading-none">
                    <span class="block">SÉ EL PRIMERO</span>
                    <span class="block">EN SABER</span>
                </h2>
        
                <p class="max-w-md text-sm">
                    Regístrate para recibir notificaciones exclusivas y acceso anticipado.
                </p>
        
                <form action="{{ route('newsletter.store') }}" method="POST" class="max-w-md space-y-6">
                    @csrf
                    <div>
                        <x-form-input name="email" type="email" placeholder="Email address" required onblack />
                    </div>
        
                    <x-primary-button class="w-full md:w-48">
                        Notifícame
                        <svg class="ml-auto h-[24px] w-[24px] stroke-current">
                            <use xlink:href="#icon-arrow-right"/>
                        </svg>
                    </x-primary-button>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection
