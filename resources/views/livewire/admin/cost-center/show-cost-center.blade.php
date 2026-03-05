<div class="row justify-content-center">
    <style>
        th,
        td {
            text-align: center;
            /* Centrar horizontalmente */
            vertical-align: middle;
            /* Centrar verticalmente */
        }

        td.long-text {
            max-width: 200px;
            /* Ajusta el ancho máximo según lo necesario */
            white-space: nowrap;
            /* Evita que el texto ocupe varias líneas */
            overflow: hidden;
            /* Oculta el texto que se desborda */
            text-overflow: ellipsis;
            /* Muestra "..." para indicar el texto truncado */
        }
    </style>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>{{ $costCenter->name }} #{{ $costCenter->id }}</h5>
                @if ($costCenter->enabled)
                    <span class="badge bg-success">Habilitado</span>
                @else
                    <span class="badge bg-danger">Deshabilitado</span>
                @endif
            </div>

            <div class="card-header" wire:ignore.self>
                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                    <li class="nav-item" wire:ignore>
                        <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basic" role="tab"
                            aria-controls="basic" aria-selected="true">Basico</a>
                    </li>
                    <li class="nav-item" wire:ignore>
                        <a class="nav-link" id="edit-tab" data-toggle="tab" href="#edit" role="tab"
                            aria-controls="edit" aria-selected="false">Editar</a>
                    </li>
                    <li class="nav-item" wire:ignore>
                        <a class="nav-link" id="requests-tab" data-toggle="tab" href="#requests" role="tab"
                            aria-controls="requests" aria-selected="false">Solicitudes</a>
                    </li>
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                        aria-expanded="false"><i class="fa-solid fa-bars" wire:ignore></i></a>
                    <div class="dropdown-menu" wire:ignore.self>
                        @if ($costCenter->enabled)
                            <button type="button" class="dropdown-item" wire:confirm='¿Deshabilitar?'
                                wire:click='disable()'>
                                Deshabilitar
                            </button>
                        @else
                            <button type="button" class="dropdown-item" wire:confirm='¿Habilitar?'
                                wire:click='enable()'>
                                Habilitar
                            </button>
                        @endif
                        <button type="button" class="dropdown-item" wire:confirm='¿Eliminar?' wire:click='delete()'>
                            Eliminar
                        </button>
                    </div>
                </ul>
            </div>

            <div class="tab-content" id="myTabContent" wire:ignore.self>
                <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab"
                    wire:ignore.self>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Empresa:</strong>
                                <p>
                                    @if ($costCenter->company)
                                        <a href="{{ route('admin.companies.show', $costCenter->company->id) }}">
                                            {{ $costCenter->company->name }}
                                        </a>
                                    @else
                                        No tiene Empresa.
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-12">
                                <strong>Descripción:</strong>
                                <p>{{ $costCenter->description ?? 'Sin descripción.' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Fecha de Registro:</strong>
                                <p>{{ $costCenter->created_at->format('d/m/Y h:i:s a') }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Última Actualización:</strong>
                                <p class="mb-0">{{ $costCenter->updated_at->format('d/m/Y h:i:s a') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab" wire:ignore.self>
                    <div class="card mb-0">
                        <form wire:submit.prevent="save">
                            <div class="card-header">
                                Editar datos
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <x-adminlte-input fgroup-class="col-md-6" name="name" label="Nombre *"
                                        placeholder="Nombre de usuario" type="text" label-class="text-lightblue"
                                        wire:model.defer="name" required autocomplete="off" />

                                    <x-adminlte-select fgroup-class="col-md-6" class="custom-select" name="company_id"
                                        label="Empresa" placeholder="Empresa" label-class="text-lightblue"
                                        enable-old-support wire:model.defer="company_id">
                                        <x-adminlte-options :options="$companiesOptions" :selected="[$company_id]"
                                            empty-option="Seleccione una Empresa" />
                                    </x-adminlte-select>

                                    <x-adminlte-textarea fgroup-class="col-md-12 mb-0" name="description"
                                        label="Descripción" rows=5 label-class="text-lightblue"
                                        placeholder="Inserte una descripción..." wire:model.defer="description"
                                        autocomplete="off">
                                    </x-adminlte-textarea>
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-end">
                                @if (session()->has('message'))
                                    <span class="text-success mr-3">{{ session('message') }}</span>
                                @endif
                                <button class="btn btn-outline-success btn-sm" type="submit">
                                    <i class="fas fa-lg fa-arrows-rotate mr-1" wire:loading.class="fa-spin"
                                        wire:target="save"></i>Actualizar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="tab-pane fade" id="requests" role="tabpanel" aria-labelledby="requests-tab"
                    wire:ignore.self>
                    <div class="card mb-0">
                        <div class="card-header">
                            Solicitudes
                        </div>
                        <div class="table-responsive">
                            <table class="{{ config('styles.table') }}">
                                <thead class="{{ config('styles.table-thead') }}">
                                    <tr>
                                        <th scope="col">id</th>
                                        <th scope="col">Monto</th>
                                        <th scope="col">Estatus</th>
                                        <th scope="col">Concepto</th>
                                        <th scope="col" style="width: 50px">Ver</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($requests as $request)
                                        <tr wire:key='request-{{ $request->id }}'>
                                            <th scope="row">{{ $request->id }}</th>
                                            <td>${{ number_format($request->amount, 2) }}</td>
                                            <td>
                                                <strong class="text-{{ $request->getStatusBSClass() }}">
                                                    {{ $request->getStatusText() }}
                                                </strong>
                                            </td>
                                            <td class="long-text">{{ $request->concept }}</td>
                                            <td>
                                                <a href="{{ route('management.requests.show', $request->id) }}"
                                                    target="_blank">
                                                    <i class="fa-solid fa-arrow-up-right-from-square text-info"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No se encontraron
                                                resultados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($requests->hasPages())
                            <div class="card-footer pb-0">
                                {{ $requests->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
