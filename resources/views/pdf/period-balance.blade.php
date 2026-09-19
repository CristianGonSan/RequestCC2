@extends('layouts.pdf.base')

@section('title', 'Balance de Periodo ' . $from->format('d/m/Y') . ' al ' . $to->format('d/m/Y'))

@section('header')
    <img src="{{ $logoBase64 }}" style="height: 50px;">
@endsection

@section('content')
    <h2 class="mb-0">Balance del Periodo</h2>
    <table class="table-layout">
        <tr>
            <td>
                <h5 class="text-muted mb-0">
                    Del {{ $from->translatedFormat('d \d\e F \d\e Y') }}
                    al {{ $to->translatedFormat('d \d\e F \d\e Y') }}
                </h5>
            </td>
            <td class="text-right">
                <h5 class="text-muted mb-4">Generado el {{ now()->translatedFormat('d \d\e F \d\e Y') }}</h5>
            </td>
        </tr>
    </table>
    <h4>Balance General</h4>
    <table class="table table-bordered mb-4">
        <thead>
            <tr>
                <th class="text-right w-25">Ingresos Totales</th>
                <th class="text-right w-25">Gastos Totales</th>
                <th class="text-right w-25">Balance</th>
                <th class="text-right w-25">Margen %</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="font-weight-bold text-right">{{ number_format($totalIncome, 2) }}</td>
                <td class="font-weight-bold text-right">{{ number_format($totalExpense, 2) }}</td>
                <td class="font-weight-bold text-right text-{{ $balance >= 0 ? 'success' : 'danger' }}">
                    {{ number_format($balance, 2) }}
                </td>
                <td class="font-weight-bold text-right text-{{ $balance >= 0 ? 'success' : 'danger' }}">
                    {{ number_format($margin, 2) }}%
                </td>
            </tr>
        </tbody>
    </table>

    <h4>Actividad General</h4>
    <table class="table table-bordered mb-4">
        <thead>
            <tr>
                <th class="text-center w-50">Solicitudes Monetarias</th>
                <th class="text-center w-50">Ingresos</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="font-weight-bold text-center">{{ number_format($moneyRequestCount, 0) }}</td>
                <td class="font-weight-bold text-center">{{ number_format($incomeCount, 0) }}</td>
            </tr>
        </tbody>
    </table>

    <h4>Balance por Centro de Costos ({{ count($costCenters) }})</h4>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Centro de Costos</th>
                <th class="text-right w-25">Ingreso</th>
                <th class="text-right w-25">Gasto</th>
                <th class="text-right w-25">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($costCenters as $costCenter)
                <tr>
                    <td>{{ $costCenter->name }}</td>
                    <td class="text-right">{{ number_format($costCenter->total_income, 2) }}</td>
                    <td class="text-right">{{ number_format($costCenter->total_expense, 2) }}</td>
                    <td class="font-weight-bold text-right text-{{ $costCenter->balance >= 0 ? 'success' : 'danger' }}">
                        {{ number_format($costCenter->balance, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>
                    Total:
                </th>
                <th class="text-right">
                    {{ number_format($totalIncome, 2) }}
                </th>
                <th class="text-right">
                    {{ number_format($totalExpense, 2) }}
                </th>
                <th class="text-right text-{{ $balance >= 0 ? 'success' : 'danger' }}">
                    {{ number_format($balance, 2)}}
                </th>
            </tr>
        </tfoot>
    </table>
@endsection

@section('footer')
    <table class="table-layout text-muted">
        <tr>
            <td>
                <p>
                    CODIAS ® {{ now()->format('Y') }} Todos los derechos reservados
                </p>
            </td>
            <td class="text-right">
                Página <span class="pagenum"></span>
            </td>
        </tr>
    </table>
@endsection
