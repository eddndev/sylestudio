{{-- 
    =================================================================
    ARCHIVO: resources/views/newsletter/confirmed.blade.php
    =================================================================
--}}
@extends('layouts.app')

@section('title', 'Suscripción Confirmada')

@section('content')
<main class="bg-surface text-on-surface">
    <div class="flex items-center justify-center min-h-screen px-6 py-24">
        <div class="text-center max-w-lg">
            {{-- Icono de Check --}}
            <svg class="mx-auto h-16 w-16 text-green-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            <h1 class="font-teko text-5xl md:text-6xl font-semibold uppercase tracking-wide text-on-surface">
                ¡Bienvenido al Club!
            </h1>
            <div class="prose prose-xl mx-auto mt-4 text-on-surface/80">
                <p>
                    Tu suscripción ha sido confirmada. A partir de ahora, serás el primero en saber sobre nuestros lanzamientos, proyectos y contenido exclusivo.
                </p>
            </div>
            <div class="mt-10">
                <a href="{{ route('shop') }}" class="btn-primary">Explora las Colecciones</a>
            </div>
        </div>
    </div>
</main>
@endsection