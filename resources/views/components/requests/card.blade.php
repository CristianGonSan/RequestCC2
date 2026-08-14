<div {{ $attributes->merge(['class' => 'card']) }}>
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div>{{ $request->created_at->format('d-m-Y h:i a') }}</div>
            <div>
                <strong class="text-{{ $request->status->bootstrapColorClass() }}">
                    {{ $request->status->label() }}
                </strong>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <span>{{ $request->user->name }}</span>
            <div>{{ $request->getFormattedAmount() }}</div>
        </div>
    </div>
    <div class="card-body py-1" style="height: 180px; overflow-y: auto;">
        <div>
            <strong>Beneficiario:</strong> {{ $request->payee ?? 'Sin datos' }}
        </div>
        <div>
            <strong>Centro de Costos:</strong> {{ $request->cost_center ?? 'Sin datos' }}
        </div>
        <div>
            <strong>Modo de Pago:</strong> <span class="badge"
                style="{{ $request->is_transfer ? 'background-color: #A3C8FF' : 'background-color: #FFB998' }}">
                {{ $request->getPaymentMethod() }}
            </span>
        </div>
        <div>
            <strong>Tipo:</strong> {{ $request->type->name }}
        </div>
        <div>
            <strong>Concepto:</strong> {{ $request->concept ?? 'Sin datos' }}
        </div>
    </div>
    <div class="card-footer">
        {{ $footer }}
    </div>
</div>
