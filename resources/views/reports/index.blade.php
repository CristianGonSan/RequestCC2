@extends('adminlte::page')

@section('plugins.Chartjs', true)
@section('plugins.Select2', true)

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Resumen</li>
        </ol>
    </nav>
    <x-alert></x-alert>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <form action="#" class="row align-items-end">
                        <div class="col-md-5">
                            <x-adminlte-input type="date" name="start_date" label="Fecha de Inicio"
                                placeholder="fecha de inicio" label-class="text-lightblue" enable-old-support
                                value="{{ $startDate->format('Y-m-d') }}">
                            </x-adminlte-input>
                        </div>

                        <div class="col-md-5">
                            <x-adminlte-input type="date" name="end_date" label="Fecha Final" placeholder="fecha final"
                                label-class="text-lightblue" enable-old-support value="{{ $endDate->format('Y-m-d') }}">
                            </x-adminlte-input>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-info btn-block mb-3">
                                <i class="fas fa-chart-pie mr-1"></i> Actualizar
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-file-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Solicitudes</span>
                            <span class="info-box-number">{{ $moneyRequestTotal }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-flag"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pagadas</span>
                            <span class="info-box-number">{{ $moneyRequestsPaid }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fas fa-dollar-sign"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Monto Pagado</span>
                            <span class="info-box-number">${{ number_format($moneyRequestsTotalAmountPaid, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <x-adminlte-card>
                <h5>Solicitudes por Tipo</h5>
                <canvas id="requestsByType">
                </canvas>
            </x-adminlte-card>
        </div>
        <div class="col-md-6">
            <x-adminlte-card>
                <h5>Monto por Tipo</h5>
                <canvas id="amountByType">
                </canvas>
            </x-adminlte-card>
        </div>
        <div class="col-md-6">
            <x-adminlte-card>
                <h5>Solicitudes por Estado</h5>
                <canvas id="requestsByStatus">
                </canvas>
            </x-adminlte-card>
        </div>
        <div class="col-md-6">
            <x-adminlte-card>
                <h5>Monto por Estado</h5>
                <canvas id="amountByStatus">
                </canvas>
            </x-adminlte-card>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 500px">
                        <table class="table table-sm table-hover table-bordered table-striped align-middle text-nowrap m-0">
                            <th scope="col">Centro de Costos</th>
                            <th scope="col">Solicitudes</th>
                            <th scope="col">Solicitudes %</th>
                            <th scope="col">Monto Total</th>
                            <th scope="col">Monto %</th>

                            <tbody>
                                @foreach ($moneyRequestsByCostCenter as $cc)
                                    <tr>
                                        <td>{{ $cc->cost_center_name }}</td>
                                        <td>{{ $cc->cost_center_count }}</td>
                                        <td>{{ $moneyRequestsPaid > 0 ? number_format((100 / $moneyRequestsPaid) * $cc->cost_center_count, 2) : '0.00' }}%
                                        </td>
                                        <td>{{ number_format($cc->amount_count, 2) }}</td>
                                        <td>{{ $moneyRequestsTotalAmountPaid > 0 ? number_format((100 / $moneyRequestsTotalAmountPaid) * $cc->amount_count, 2) : '0.00' }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="col-md-12">
                <p>Esta página presenta un resumen basico de las solicitudes realizadas dentro de un rango de fechas
                    específico, el
                    cual puede ajustarse según sea necesario. Las gráficas solo incluyen datos de solicitudes cuyo estado es
                    <strong class="text-primary">PAGADA</strong>.
                </p>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Si usas Chart.js v4 vía ES modules (import { Chart } from 'chart.js'),
        // necesitas registrar los componentes que vas a usar:
        if (typeof Chart !== 'undefined' && Chart.register) {
            Chart.register(
                Chart.ArcElement,
                Chart.PieController,
                Chart.Legend,
                Chart.Tooltip
            );
        }

        /**
         * Crea un gráfico de tipo "pie".
         *
         * @param {string} canvasId - El ID del elemento canvas donde se renderiza el gráfico.
         * @param {Array<string>} labels - Las etiquetas para los segmentos del gráfico.
         * @param {Array<number>} data - Los datos numéricos correspondientes a cada etiqueta.
         * @param {string} label - La etiqueta del conjunto de datos.
         */
        function createPieChart(canvasId, labels, data, label) {
            const ctx = document.getElementById(canvasId).getContext('2d');

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        backgroundColor: getColors(labels.length),
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.label + ': ' + context.raw;
                                }
                            }
                        }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Verifica que estos valores están bien definidos y contienen datos
            const labelsByType = @json($moneyRequestsByType['labels']);
            const dataByType = @json($moneyRequestsByType['type_count']);
            const amountByType = @json($moneyRequestsByType['amount_count']);

            const labelsByStatus = @json($moneyRequestsByStatus['labels']);
            const dataByStatus = @json($moneyRequestsByStatus['status_count']);
            const amountByStatus = @json($moneyRequestsByStatus['amount_count']);

            createPieChart('requestsByType', labelsByType, dataByType, 'X');
            createPieChart('amountByType', labelsByType, amountByType, 'X');

            createPieChart('requestsByStatus', labelsByStatus, dataByStatus, 'X');
            createPieChart('amountByStatus', labelsByStatus, amountByStatus, 'X');
        });

        function getColors(n) {
            let colors = [];

            for (let i = 0; i < n; i++) {
                colors.push(getColorFromSeed(i));
            }

            return colors;
        }

        function getColorFromSeed(seed) {
            let r = Math.floor(seededRandom(seed) * 256);
            let g = Math.floor(seededRandom(seed + 1) * 256);
            let b = Math.floor(seededRandom(seed + 2) * 256);

            return `rgba(${r}, ${g}, ${b}, 0.6)`;
        }

        function seededRandom(seed) {
            let x = Math.sin(seed) * 10000;
            return x - Math.floor(x);
        }
    </script>
@endsection
