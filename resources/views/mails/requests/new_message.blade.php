<x-mail::message>
# Tienes un nuevo mensaje

**Fecha y Hora:** {{ $messageModel->created_at?->format('d/m/Y h:i:s a') }}

**{{ $messageModel->user->name }}** ({{ $messageModel->user->email }}) escribió:

<x-mail::panel>
{{ $messageModel->message }}
</x-mail::panel>

<x-mail::button :url="route('requests.show', $messageModel->request->id)">
Ver Solicitud #{{ $messageModel->request->id }}
</x-mail::button>

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
