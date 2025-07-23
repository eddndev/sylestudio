@extends('layouts.app')

@section('content')
    <div class="w-full max-w-sm mx-auto py-20 space-y-8">
        <x-form-title>BIENVENIDO</x-form-title>
        <x-form-description>
            Por favor introduce tu email y contraseña
        </x-form-description>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            {{-- Email --}}
            <x-form-input  type="email"
                           name="email"
                           placeholder="Correo electrónico"
                           :value="old('email')"
                           required
                           autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />

            {{-- Password + enlace --}}
            <div class="relative">
                <x-form-input  type="password"
                               name="password"
                               placeholder="Contraseña"
                               required
                               autocomplete="current-password"
                               class="pr-32" />

                @if (Route::has('password.request'))
                    <x-form-helper-link
                        href="{{ route('password.request') }}"
                        class="absolute right-0 top-2">
                        Olvidé mi contraseña
                    </x-form-helper-link>
                @endif

                <x-input-error :messages="$errors->get('password')" />
            </div>

            <x-primary-button class="w-full">
                INICIAR SESIÓN
            </x-primary-button>
        </form>

        <p class="text-center text-sm text-on-surface/80">
            ¿Aún no tienes cuenta?
            <x-form-helper-link href="{{ route('register') }}" class="font-medium">
                Crear una cuenta aquí
            </x-form-helper-link>
        </p>
    </div>
@endsection
