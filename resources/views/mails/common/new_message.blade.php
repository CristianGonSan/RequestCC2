<x-mail::message>
# Tienes un nuevo mensaje

**Fecha y Hora:** {{ $messageDate }}

**{{ $sender }}** escribió:

<x-mail::panel>
{{ $message }}
</x-mail::panel>

<x-mail::button :url="$messageableUrl">
Ver {{ $messageableLabel }}
</x-mail::button>

Saludos, {{ config('app.name') }}
</x-mail::message>
