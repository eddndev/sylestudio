@extends('layouts.app')

@section('content')
    <div class="w-full max-w-sm mx-auto py-20 space-y-8">
        <x-form-title>NUEVA CONTRASEÑA</x-form-title>
        <x-form-description>
            Escribe tu nueva contraseña para la cuenta.
        </x-form-description>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <x-form-input type="email"
                          name="email"
                          placeholder="Correo electrónico"
                          :value="old('email', $request->email)"
                          required
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />

            <x-form-input type="password"
                          name="password"
                          placeholder="Contraseña"
                          required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />

            <x-form-input type="password"
                          name="password_confirmation"
                          placeholder="Confirmar contraseña"
                          required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />

            <x-primary-button class="w-full">
                RESTABLECER
            </x-primary-button>
        </form>
    </div>
@endsection
