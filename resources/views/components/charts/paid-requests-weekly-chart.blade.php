<div class="card card-outline card-navy">
    <div class="card-header">
        <h3 class="card-title">
            @if ($onlyCurrentUser)
                Mis solicitudes pagadas
            @else
                Solicitudes pagadas
            @endif
        </h3>
        <div class="card-tools">
            <span class="text-muted small mr-2">Últimos 7 días</span>
        </div>
    </div>

    <div class="card-body pt-2">
        <div class="row mb-3">
            <div class="col-6">
                <span class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: .05em;">Total del
                    periodo</span>
                <h4 class="mb-0 font-weight-bold" id="{{ $canvasId }}Total"
                    style="font-variant-numeric: tabular-nums;">—</h4>
            </div>
            <div class="col-6 text-right">
                <span class="text-uppercase text-muted" style="font-size: 11px; letter-spacing: .05em;">Promedio
                    diario</span>
                <h4 class="mb-0 font-weight-bold text-muted" id="{{ $canvasId }}Avg"
                    style="font-variant-numeric: tabular-nums;">—</h4>
            </div>
        </div>

        <div class="chart-wrapper" style="position: relative; height: 150px;">
            <canvas id="{{ $canvasId }}"></canvas>
        </div>

        <p class="text-muted text-center mt-4 mb-0" id="{{ $canvasId }}Empty"
            style="display: none; font-size: 13px;">
            No hay solicitudes pagadas registradas en este periodo.
        </p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const labels = @json($labels);
        const totals = @json($totals);

        const canvasElement = document.getElementById('{{ $canvasId }}');
        const emptyMessage = document.getElementById('{{ $canvasId }}Empty');
        const totalEl = document.getElementById('{{ $canvasId }}Total');
        const avgEl = document.getElementById('{{ $canvasId }}Avg');

        const fmt = (value) => '$' + Number(value).toLocaleString('es-MX', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        const totalAmount = totals.reduce((sum, value) => sum + Number(value), 0);
        const avgAmount = totals.length ? totalAmount / totals.length : 0;

        totalEl.textContent = fmt(totalAmount);
        avgEl.textContent = fmt(avgAmount);

        if (!totals.length || totalAmount === 0) {
            canvasElement.style.display = 'none';
            emptyMessage.style.display = 'block';
            return;
        }

        const NAVY = '#1e3a5f';
        const AXIS_GRAY = '#8a94a3';
        const GRID_LINE = '#eceff2';

        const chartContext = canvasElement.getContext('2d');

        new Chart(chartContext, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Monto pagado',
                    data: totals,
                    backgroundColor: NAVY,
                    hoverBackgroundColor: '#14293f',
                    borderRadius: 2,
                    borderSkipped: false,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 300,
                    easing: 'easeOutQuad'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                layout: {
                    padding: {
                        top: 8
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        border: {
                            color: '#dfe3e8'
                        },
                        ticks: {
                            color: AXIS_GRAY,
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        border: {
                            display: false
                        },
                        grid: {
                            color: GRID_LINE,
                            drawTicks: false
                        },
                        ticks: {
                            color: AXIS_GRAY,
                            font: {
                                size: 11
                            },
                            maxTicksLimit: 5,
                            callback: function(value) {
                                return '$' + value.toLocaleString('es-MX');
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#fff',
                        titleFont: {
                            size: 11,
                            weight: '600'
                        },
                        bodyColor: '#e2e8f0',
                        bodyFont: {
                            size: 12
                        },
                        padding: 10,
                        cornerRadius: 3,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return fmt(context.parsed.y);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
