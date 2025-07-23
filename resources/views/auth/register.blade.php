@extends('layouts.app')

@section('content')
    <div class="w-full max-w-sm mx-auto py-20 space-y-8">
        <x-form-title>CREA TU CUENTA</x-form-title>
        <x-form-description>
            Completa el formulario para registrarte.
        </x-form-description>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            {{-- Nombre --}}
            <x-form-input  type="text"
                           name="name"
                           placeholder="Nombre"
                           :value="old('name')"
                           required
                           autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />

            {{-- Email --}}
            <x-form-input  type="email"
                           name="email"
                           placeholder="Correo electrónico"
                           :value="old('email')"
                           required
                           autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />

            {{-- Password --}}
            <x-form-input  type="password"
                           name="password"
                           placeholder="Contraseña"
                           required
                           autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />

            {{-- Confirm Password --}}
            <x-form-input  type="password"
                           name="password_confirmation"
                           placeholder="Confirmar contraseña"
                           required
                           autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />

            <x-primary-button class="w-full">
                REGISTRAR
            </x-primary-button>
        </form>

        <p class="text-center text-sm text-on-surface/80">
            ¿Ya tienes cuenta?
            <x-form-helper-link href="{{ route('login') }}" class="font-medium">
                Inicia sesión aquí
            </x-form-helper-link>
        </p>
    </div>
@endsection
