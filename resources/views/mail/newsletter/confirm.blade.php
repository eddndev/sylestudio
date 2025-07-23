<x-mail::message>
# ¡Confirma tu suscripción!

<x-mail::button :url="$url">
Confirmar suscripción
</x-mail::button>

Si el botón no funciona, copia y pega este enlace: {{ $url }}

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
