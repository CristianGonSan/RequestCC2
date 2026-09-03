<x-mail::message>
    # Cambio de Estado de Solicitud

    **Fecha y Hora:** {{ $request->updated_at?->format('d/m/Y h:i:s a') }}

    El estado de la solicitud **#{{ $request->id }}** ha cambiado a **{{ $request->status->label() }}**.

    <x-mail::panel>
        **Solicitante:** {{ $request->user->name }}
        **Centro de Costos:** {{ $request->costCenter?->name ?? 'N/A' }}
        **Tipo de Solicitud:** {{ $request->is_transfer ? 'Transferencia' : 'Efectivo' }}
        **Monto:** ${{ number_format($request->amount, 2) }}
        **Concepto:** {{ $request->concept }}
    </x-mail::panel>

    <x-mail::button :url="route('money-requests.show', $request->id)">
        Ver Solicitud (Propia)
    </x-mail::button>

    <x-mail::button :url="route('management.money-requests.show', $request->id)" color="success">
        Ver Solicitud (Admin)
    </x-mail::button>

    <x-mail::button :url="route('accounting.money-requests.show', $request->id)" color="success">
        Ver Solicitud (Contabilidad)
    </x-mail::button>

    Saludos,<br>
    {{ config('app.name') }}
</x-mail::message>