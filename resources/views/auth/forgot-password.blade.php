@extends('layouts.app')

@section('content')
    <div class="w-full max-w-sm mx-auto py-20 space-y-8">
        <x-form-title>RECUPERAR CONTRASEÑA</x-form-title>
        <x-form-description>
            Ingresa tu correo y te enviaremos un enlace para restablecerla.
        </x-form-description>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf
            <x-form-input type="email"
                          name="email"
                          placeholder="Correo electrónico"
                          :value="old('email')"
                          required
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />

            <x-primary-button class="w-full">
                ENVIAR ENLACE
            </x-primary-button>
        </form>

        <p class="text-center text-sm text-on-surface/80">
            ¿Recordaste tu contraseña?
            <x-form-helper-link href="{{ route('login') }}" class="font-medium">
                Volver a iniciar sesión
            </x-form-helper-link>
        </p>
    </div>
@endsection
