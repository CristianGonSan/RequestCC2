<div>
    <div class="row">
        <div class="col-md-4 col-6">
            <x-adminlte-input type="date" name="date_from" wire:model="dateFrom" wire:change='updatePeriod'>
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
        </div>
        <div class="col-md-4 col-6">
            <x-adminlte-input type="date" name="date_to" wire:model="dateTo" wire:change='updatePeriod'>
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
        </div>
        <div class="col-md-2 col-6">
            <x-livewire.loading-button label="Exportar Excel" icon="file-excel" theme="outline-success" class="w-100"
                wire:click='exportExcel' wire:target='exportExcel' />
        </div>
        <div class="col-md-2 col-6">
            <x-livewire.loading-button label="Exportar PDF" icon="file-pdf" theme="outline-warning" class="w-100"
                wire:click='exportPdf' wire:target='exportPdf' />
        </div>
    </div>

    <hr class="mt-0">

    <div class="row align-items-stretch">
        <div class="col-md-12">
            <div class="row">
                @php
                    $balanceTheme = $balance >= 0 ? 'success' : 'danger';
                @endphp
                <div class="col-md-3 col-sm-6">
                    <x-adminlte-info-box title="Ingresos" text="{{ number_format($totalIncome, 2) }}"
                        icon="fas fa-arrow-trend-up" icon-theme="success" />
                </div>

                <div class="col-md-3 col-sm-6">
                    <x-adminlte-info-box title="Gastos" text="{{ number_format($totalExpense, 2) }}"
                        icon="fas fa-arrow-trend-down" icon-theme="danger" />
                </div>

                <div class="col-md-3 col-sm-6">
                    <x-adminlte-info-box title="Balance {{ $balance >= 0 ? 'positivo' : 'negativo' }}"
                        text="{{ number_format($balance, 2) }}"
                        icon="fas fa-{{ $balance >= 0 ? 'scale-unbalanced' : 'scale-unbalanced-flip' }}"
                        icon-theme="{{ $balanceTheme }}" />
                </div>

                <div class="col-md-3 col-sm-6">
                    <x-adminlte-info-box title="Margen de {{ $balance >= 0 ? 'ganancias' : 'perdidas' }}"
                        text="{{ number_format($margin, 1) }}%" icon="fas fa-percent"
                        icon-theme="{{ $balanceTheme }}" />
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-3">
            <div class="card h-100 mb-0">
                <div class="card-body p-3">
                    <div wire:ignore style="width:100%; min-height:280px; height:100%;">
                        <canvas id="balanceScatterPlot"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100 mb-0">
                <div class="card-body d-flex align-items-center justify-content-center p-3">
                    <div wire:ignore style="width:100%; max-width:220px; height:220px;">
                        <canvas id="incomeExpenseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <x-card-table :pagination="$balanceCostCenters">
                {{ $this->thead() }}

                <tbody>
                    @foreach ($balanceCostCenters as $costCenter)
                        @php
                            $company = $costCenter->company;
                            $income = $costCenter->total_income;
                            $expense = $costCenter->total_expense;
                            $balance = $costCenter->balance;
                        @endphp
                        <tr>
                            <td>
                                <div class="text-truncate" title="{{ $costCenter->name }} - {{ $company->name }}">
                                    <strong>{{ $costCenter->name }}</strong><span> -
                                    </span><span>{{ $company->name }}</span>
                                </div>
                            </td>
                            <td>
                                {{ number_format($income, 2) }}
                            </td>
                            <td>
                                {{ number_format($expense, 2) }}
                            </td>
                            <td class="font-weight-bold {{ $balance < 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($balance, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-card-table>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener('livewire:init', () => {

            // Formatea con punto decimal y coma de miles (en-US), sin importar
            // el locale configurado en el navegador del usuario.
            const formatNumber = (value) => (Number(value) || 0).toLocaleString('en-US');

            const ctxDoughnut = document.getElementById('incomeExpenseChart').getContext('2d');

            const doughnutChart = new Chart(ctxDoughnut, {
                type: 'doughnut',
                data: {
                    labels: ['Gastos', 'Ingresos'],
                    datasets: [{
                        data: [0, 0],
                        backgroundColor: ['#dc3545', '#28a745'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const label = context.label || '';
                                    const value = formatNumber(context.raw);
                                    return `${label}: ${value}`;
                                }
                            }
                        }
                    }
                }
            });

            Livewire.on('income-expense-chart-updated', (data) => {
                doughnutChart.data.datasets[0].data = [data.expenses, data.incomes];
                doughnutChart.update();
            });

            const ctxScatter = document.getElementById('balanceScatterPlot').getContext('2d');

            const scatterChart = new Chart(ctxScatter, {
                type: 'scatter',
                data: {
                    datasets: [
                        {
                            label: 'Centros de Costo',
                            data: [],
                            pointBackgroundColor: (ctx) => {
                                const raw = ctx.raw;
                                if (!raw) return 'rgba(108, 117, 125, 0.7)';
                                const balance = raw.y - raw.x;
                                if (balance > 0) return 'rgba(40, 167, 69, 0.7)';
                                if (balance < 0) return 'rgba(220, 53, 69, 0.7)';
                                return 'rgba(108, 117, 125, 0.7)';
                            },
                            borderWidth: 1,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        },
                        {
                            type: 'line',
                            label: 'Punto de Equilibrio (Ingresos = Gastos)',
                            data: [],
                            borderColor: 'rgba(108, 117, 125, 0.8)',
                            borderWidth: 2,
                            borderDash: [6, 6],
                            pointRadius: 0,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: { display: true, text: 'Gastos ($)' },
                            // max se fija dinámicamente al recibir datos, igual que en y,
                            // para que ambos ejes compartan la misma escala.
                            ticks: {
                                callback: (value) => formatNumber(value)
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Ingresos ($)' },
                            ticks: {
                                callback: (value) => formatNumber(value)
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            filter: (item) => item.datasetIndex === 0,
                            callbacks: {
                                label: (context) => {
                                    const raw = context.raw;
                                    if (!raw || raw.costCenter === undefined) return '';
                                    const balance = raw.y - raw.x;
                                    return `${raw.costCenter} | Ingresos: ${formatNumber(raw.y)} - Gastos: ${formatNumber(raw.x)} | Balance : ${formatNumber(balance)}`;
                                }
                            }
                        }
                    }
                }
            });

            Livewire.on('balance-scatter-plot-updated', (data) => {
                const dataset = data.dataset;

                scatterChart.data.datasets[0].data = dataset.map(item => ({
                    x: item.expense,
                    y: item.income,
                    costCenter: item.costCenter
                }));

                const maxVal = Math.max(
                    ...dataset.map(i => Math.max(i.expense || 0, i.income || 0)),
                    0
                );
                const axisMax = maxVal > 0 ? maxVal * 1.05 : 1;

                scatterChart.options.scales.x.max = axisMax;
                scatterChart.options.scales.y.max = axisMax;

                scatterChart.data.datasets[1].data = [
                    { x: 0, y: 0 },
                    { x: axisMax, y: axisMax }
                ];

                scatterChart.update();
            });
        });
    </script>
@endpush
