@extends('adminlte::page')

@section('plugins.Select2', true)
@section('plugins.InputMask', true)

@section('title_prefix', 'Exportar Solicitudes de Dinero |')

@section('content_header')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Exportar</li>
                <li class="breadcrumb-item active">Solicitudes de Dinero</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="container">
        <p class="text-muted">
            Aplique filtros escribiendo en los campos correspondientes. Los campos vacíos o no seleccionados
            no serán considerados, lo que devolverá los datos sin dichos filtros. Cuantos más filtros
            aplique, más precisos serán los resultados. El sistema no diferencia entre MAYÚSCULAS y minúsculas.
        </p>

        <h1 class="h4 mb-3">Exportar v2</h1>

        <form class="row" action="{{ route('export.money-requests.download') }}" method="GET">
            <!-- Panel de Filtros -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Filtros</h2>
                    </div>
                    <div class="card-body">
                        <!-- Fecha de Creación -->
                        <div class="form-row">
                            <div class="col-12 mb-2">Filtrar por fecha de creación</div>
                            <x-adminlte-input fgroup-class="col-6" type="date" name="created_at_start" label="Desde"
                                enable-old-support />
                            <x-adminlte-input fgroup-class="col-6" type="date" name="created_at_end" label="Hasta"
                                enable-old-support />
                        </div>

                        <hr class="my-3">

                        <!-- Fecha de Actualización -->
                        <div class="form-row">
                            <div class="col-12 mb-2">Filtrar por fecha de actualización</div>
                            <x-adminlte-input fgroup-class="col-6" type="date" name="updated_at_start" label="Desde"
                                enable-old-support />
                            <x-adminlte-input fgroup-class="col-6" type="date" name="updated_at_end" label="Hasta"
                                enable-old-support />
                        </div>

                        <hr class="my-3">

                        <!-- Ordenamiento -->
                        <div class="form-row">
                            <x-adminlte-select fgroup-class="col-md-6" id="orderBy" name="orderBy" class="custom-select"
                                label="Ordenar Por">
                                <option value="created_at">Ordenar por Fecha de Creación</option>
                                <option value="updated_at">Ordenar por Fecha de Actualización</option>
                                <option value="cost_center">Ordenar por Centros de Costos</option>
                                <option value="amount">Ordenar por Importe</option>
                                <option value="id">Ordenar por ID</option>
                            </x-adminlte-select>

                            <x-adminlte-select fgroup-class="col-md-6" id="orderDirection" name="orderDirection"
                                class="custom-select" label="Orden">
                                <option value="desc">Descendente</option>
                                <option value="asc">Ascendente</option>
                            </x-adminlte-select>
                        </div>

                        <hr class="my-3">

                        <!-- Filtro de Monto -->
                        <div class="form-row">
                            <div class="col-12 mb-2">Filtrar por monto</div>
                            <x-adminlte-input class="amount" type="text" fgroup-class="col-6" name="min_amount"
                                label="Desde" placeholder="monto mínimo" enable-old-support />
                            <x-adminlte-input class="amount" type="text" fgroup-class="col-6" name="max_amount"
                                label="Hasta" placeholder="monto máximo" enable-old-support />
                        </div>

                        <hr class="my-3">

                        <!-- Selects Múltiples -->
                        <div class="form-row">
                            <x-adminlte-select fgroup-class="col-md-6" id="costCenters" name="cost_centers[]"
                                label="Centro/s de Costos" multiple />
                            <x-adminlte-select fgroup-class="col-md-6" id="users" name="users[]" label="Usuario/s"
                                multiple />
                        </div>

                        <hr class="my-3">

                        <!-- Detalles de Pago -->
                        <div class="form-row">
                            <x-adminlte-input fgroup-class="col-md-6" name="payee" label="Titular" placeholder="titular"
                                enable-old-support />
                            <x-adminlte-input fgroup-class="col-md-6" name="bank" label="Banco" placeholder="banco"
                                enable-old-support />

                            <x-adminlte-textarea fgroup-class="col-12" name="concept" label="Concepto/s" rows="3"
                                igroup-size="sm" placeholder="use la pleca ( | ) para agregar varios conceptos"
                                enable-old-support></x-adminlte-textarea>
                        </div>

                        <hr class="my-3">

                        <!-- Tipo y Estado -->
                        <div class="form-row">
                            @php
                                $config = [
                                    'placeholder' => 'Selecciona multiples opciones...',
                                    'allowClear' => true,
                                ];
                            @endphp

                            <x-adminlte-select2 fgroup-class="col-12" id="type" name="type[]" label="Tipo"
                                :config="$config" enable-old-support multiple>
                                <x-adminlte-options :options="$typeOptions" />
                            </x-adminlte-select2>

                            <x-adminlte-select2 fgroup-class="col-12" id="status" name="status[]" label="Estado"
                                :config="$config" enable-old-support multiple>
                                <x-adminlte-options :options="$statusOptions" />
                            </x-adminlte-select2>

                            <!-- Método de Pago -->
                            <x-adminlte-select fgroup-class="col-12" class="custom-select" name="is_transfer"
                                label="Método de pago">
                                <option value="">Sin método especifico</option>
                                <option value="cash">Efectivo</option>
                                <option value="transfer">Transferencia</option>
                            </x-adminlte-select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de Columnas -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Columnas a Exportar</h2>
                    </div>
                    <ul class="list-group list-group-flush overflow-auto">
                        @forelse ($columnsOptions as $key => $column)
                            <li class="list-group-item py-1">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="column_{{ $key }}"
                                        name="columns[{{ $key }}]" value="{{ $key }}" checked />
                                    <label for="column_{{ $key }}">{{ $column }}</label>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No hay datos.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Botón de Envío -->
            <div class="col-12 mb-3">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-lg fa-file-excel mr-1"></i>
                    Generar
                </button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        $(".amount").inputmask({
            alias: "numeric",
            groupSeparator: ",",
            radixPoint: ".",
            autoGroup: true,
            digits: 2,
            digitsOptional: false,
            rightAlign: false
        });

        const baseAjax = {
            dataType: 'json',
            delay: 300,
            data: params => ({
                search: params.term,
                page: params.page || 1
            }),
            cache: true
        };

        const baseSelect2 = {
            theme: 'bootstrap4',
            language: "es",
            width: '100%',
            allowClear: true,
            minimumInputLength: 1
        };

        $('#costCenters').select2({
            ...baseSelect2,
            placeholder: 'Buscar centros de costos',
            ajax: {
                ...baseAjax,
                url: "{{ route('export.lookups.cost-centers.select2') }}",
                processResults: data => ({
                    results: data.results.map(item => ({
                        id: item.text,
                        text: item.text,
                        company: item.company,
                        description: item.description
                    })),
                    pagination: {
                        more: data.pagination.more
                    }
                })
            },
            templateResult: data => {
                if (data.loading) return data.text;
                return $(`
                    <div class="p-1">
                        <strong>${data.text}</strong>
                        <small class="d-block font-weight-bold">${data.company ?? ''}</small>
                        <small>${data.description ?? ''}</small>
                    </div>
                `);
            }
        });

        $('#users').select2({
            ...baseSelect2,
            placeholder: 'Buscar usuarios',
            ajax: {
                ...baseAjax,
                url: "{{ route('export.lookups.users.select2') }}",
                processResults: data => ({
                    results: data.results,
                    pagination: {
                        more: data.pagination.more
                    }
                })
            },
            templateResult: data => {
                if (data.loading) return data.text;
                return $(`
                    <div class="p-1">
                        <strong>${data.text}</strong>
                        <small class="d-block">${data.email}</small>
                    </div>
                `);
            }
        });
    </script>
@endsection
