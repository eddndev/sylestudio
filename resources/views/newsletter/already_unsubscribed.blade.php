
{{-- 
    =================================================================
    ARCHIVO: resources/views/newsletter/already_unsubscribed.blade.php
    =================================================================
--}}
@extends('layouts.app')

@section('title', 'Ya Estabas Desuscrito')

@section('content')
<main class="bg-surface text-on-surface">
    <div class="flex items-center justify-center min-h-screen px-6 py-24">
        <div class="text-center max-w-lg">
            {{-- Icono de Información --}}
            <svg class="mx-auto h-16 w-16 text-on-surface/40 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>

            <h1 class="font-teko text-5xl md:text-6xl font-semibold uppercase tracking-wide text-on-surface">
                No se Requiere Acción
            </h1>
            <div class="prose prose-xl mx-auto mt-4 text-on-surface/80">
                <p>
                    Tu dirección de correo electrónico no se encontraba en nuestra lista de suscriptores, por lo que no fue necesaria ninguna acción.
                </p>
            </div>
            <div class="mt-10">
                <a href="{{ route('home') }}" class="btn-secondary">Volver al Inicio</a>
            </div>
        </div>
    </div>
</main>
@endsection
