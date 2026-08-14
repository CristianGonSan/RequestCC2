<x-mail::message>
# Cuenta Creada

**Fecha y Hora:** {{ $user->created_at->format('d/m/Y h:i:s a') }}

Su cuenta ha sido registrada y habilitada para usar el sistema de Centro de Costos.

<x-mail::panel>
**Nombre de Usuario:** {{ $user->name }}
**Correo:** {{ $user->email }}
**Contraseña:** {{ $password }}
</x-mail::panel>

Puede iniciar sesión con sus credenciales haciendo clic en el siguiente botón:

<x-mail::button :url="route('login')">
Iniciar Sesión
</x-mail::button>

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
