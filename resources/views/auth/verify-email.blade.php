@extends('layouts.app')

@section('content')
    <div class="w-full max-w-sm mx-auto py-20 space-y-8">
        <x-form-title>VERIFICA TU EMAIL</x-form-title>
        <x-form-description>
            Te hemos enviado un enlace de verificación a tu correo.  
            Si no lo recibiste, pulsa el botón para reenviarlo.
        </x-form-description>

        @if (session('status') === 'verification-link-sent')
            <p class="text-center text-sm text-green-600">
                Se envió un nuevo enlace a tu correo.
            </p>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="space-y-6">
            @csrf
            <x-primary-button class="w-full">
                REENVIAR ENLACE
            </x-primary-button>
        </form>

        <p class="text-center text-sm text-on-surface/80">
            <x-form-helper-link href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                Cerrar sesión
            </x-form-helper-link>
        </p>
    </div>
@endsection
