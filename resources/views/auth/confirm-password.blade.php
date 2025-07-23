@extends('layouts.app')

@section('content')
    <div class="w-full max-w-sm mx-auto py-20 space-y-8">
        <x-form-title>CONFIRMA TU CONTRASEÑA</x-form-title>
        <x-form-description>
            Por seguridad, introduce tu contraseña antes de continuar.
        </x-form-description>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
            @csrf

            <x-form-input type="password"
                          name="password"
                          placeholder="Contraseña"
                          required
                          autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />

            <x-primary-button class="w-full">
                CONFIRMAR
            </x-primary-button>
        </form>
    </div>
@endsection
