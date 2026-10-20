<x-mail::message>
# Tienes un nuevo mensaje

**Fecha y Hora:** {{ $dateTime }}

**{{ $user }}** escribió:

<x-mail::panel>
{{ $message }}
</x-mail::panel>

@if ($buttonUrl)
<x-mail::button :url="$buttonUrl">
Ver mensaje
</x-mail::button>
@endif

Saludos, {{ config('app.name') }}
</x-mail::message>
