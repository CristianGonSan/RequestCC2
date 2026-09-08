<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fa-solid fa-scale-balanced mr-1"></i>
            Balance por empresa
        </h3>
        <div class="card-tools text-muted small">
            {{ $from->format('d/m/Y') }} - {{ $to->format('d/m/Y') }}
        </div>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th class="text-right">Gasto</th>
                    <th class="text-right">Ingreso</th>
                    <th class="text-right">Balance</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($balances as $row)
                    <tr>
                        <td>{{ $row['company'] }}</td>
                        <td class="text-right text-danger">
                            $ {{ number_format($row['expense'], 2) }}
                        </td>
                        <td class="text-right text-success">
                            $ {{ number_format($row['income'], 2) }}
                        </td>
                        <td class="text-right font-weight-bold {{ $row['balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fa-solid {{ $row['balance'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                            $ {{ number_format($row['balance'], 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            No hay información en el rango seleccionado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
