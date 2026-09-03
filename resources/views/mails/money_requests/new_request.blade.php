<x-mail::message>
    # Nueva Solicitud

    **Fecha y Hora:** {{ $request->created_at?->format('d/m/Y h:i:s a') }}

    **{{ $request->user->name }}** ha realizado una nueva solicitud al centro de costos
    **{{ $request->costCenter?->name ?? 'N/A' }}**.

    <x-mail::panel>
        **Tipo de Solicitud**
        {{ $request->is_transfer ? 'Transferencia' : 'Efectivo' }}

        **Monto**
        ${{ number_format($request->amount, 2) }}

        **Concepto**
        {{ $request->concept }}
    </x-mail::panel>

    <x-mail::button :url="route('management.money-requests.show', $request->id)">
        Ver Solicitud
    </x-mail::button>

    Saludos,<br>
    {{ config('app.name') }}
</x-mail::message>