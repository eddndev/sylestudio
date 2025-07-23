{{-- 
    =================================================================
    ARCHIVO: resources/views/newsletter/pending.blade.php
    =================================================================
--}}
@extends('layouts.app')

@section('title', 'Confirma tu Suscripción')

@section('content')
<main class="bg-surface text-on-surface">
    <div class="flex items-center justify-center min-h-screen px-6 py-24">
        <div class="text-center max-w-lg">

            {{-- Flash message opcional --}}
            @if(session('status'))
                <div class="mb-8 px-4 py-3 rounded bg-primary text-white text-sm">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Icono de Correo --}}
            <svg class="mx-auto h-16 w-16 text-on-surface/40 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>

            <h1 class="font-teko text-5xl md:text-6xl font-semibold uppercase tracking-wide text-on-surface">
                Un Último Paso
            </h1>

            <div class="prose prose-xl mx-auto mt-4 text-on-surface/80">
                <p>
                    Hemos enviado un correo de confirmación a tu bandeja de entrada. Por favor, haz clic en el enlace
                    que contiene para unirte oficialmente a nuestro club.
                </p>
            </div>

            <div class="mt-10">
                <a href="{{ route('home') }}" class="btn-secondary">Volver al Inicio</a>
            </div>
        </div>
    </div>
</main>
@endsection
