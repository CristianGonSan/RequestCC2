@extends('adminlte::page')

@section('plugins.Chartjs', true)
@section('plugins.Select2', true)
@section('plugins.Flatpickr', true)

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
                            <div class="flatpickrStart">
                                <x-adminlte-input name="start_date" label="Fecha de Inicio" placeholder="fecha de inicio"
                                    label-class="text-lightblue" enable-old-support data-input>
                                </x-adminlte-input>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="flatpickrEnd">
                                <x-adminlte-input name="end_date" label="Fecha Final" placeholder="fecha final"
                                    label-class="text-lightblue" enable-old-support data-input>
                                </x-adminlte-input>
                            </div>
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
                            <span class="info-box-number">{{ $requestTotal }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-flag"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pagadas</span>
                            <span class="info-box-number">{{ $requestsPaid }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fas fa-dollar-sign"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Monto Pagado</span>
                            <span class="info-box-number">${{ number_format($totalAmountPaid, 2) }}</span>
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
                                @foreach ($requestsByCostCenter as $cc)
                                    <tr>
                                        <td>{{ $cc->cost_center }}</td>
                                        <td>{{ $cc->cost_center_count }}</td>
                                        <td>{{ number_format((100 / $requestsPaid) * $cc->cost_center_count, 2) }}%</td>
                                        <td>{{ number_format($cc->amount_count, 2) }}</td>
                                        <td>{{ number_format((100 / $totalAmountPaid) * $cc->amount_count, 2) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

            </div>
        </div>

        <div class="col-md-12">
            <p>Esta página presenta un resumen basico de las solicitudes realizadas dentro de un rango de fechas específico, el
                cual puede ajustarse según sea necesario. Las gráficas solo incluyen datos de solicitudes cuyo estado es
                <strong class="text-primary">PAGADA</strong>.</p>
        </div>
    </div>
@endsection

@section('js')
    <script>
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
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Verifica que estos valores están bien definidos y contienen datos
            const labelsByType = @json($requestsByType['labels']);
            const dataByType = @json($requestsByType['type_count']);
            const amountByType = @json($requestsByType['amount_count']);

            const labelsByStatus = @json($requestsByStatus['labels']);
            const dataByStatus = @json($requestsByStatus['status_count']);
            const amountByStatus = @json($requestsByStatus['amount_count']);

            createPieChart('requestsByType', labelsByType, dataByType, 'X');
            createPieChart('amountByType', labelsByType, amountByType, 'X');

            createPieChart('requestsByStatus', labelsByStatus, dataByStatus, 'X');
            createPieChart('amountByStatus', labelsByStatus, amountByStatus, 'X');
        });

        $(".flatpickrStart").flatpickr({
            defaultDate: @json($startDate->format('Y-m-d')),
            maxDate: 'today',
            weekNumbers: true,
            wrap: true,
            locale: {
                weekdays: {
                    shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                    longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                },
                months: {
                    shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
                    longhand: ['Enero', 'Febreo', 'Мarzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
                        'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                    ],
                },
            },
        });

        $(".flatpickrEnd").flatpickr({
            defaultDate: @json($endDate->format('Y-m-d')),
            maxDate: 'today',
            weekNumbers: true,
            wrap: true,
            locale: {
                weekdays: {
                    shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                    longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                },
                months: {
                    shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
                    longhand: ['Enero', 'Febreo', 'Мarzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
                        'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                    ],
                },
            },
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
