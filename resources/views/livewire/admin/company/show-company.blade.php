@section('plugins.Sweetalert2', true)

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
                <h5>{{ $company->name }} #{{ $company->id }}</h5>
                @if ($company->enabled)
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
                        <a class="nav-link" id="costCenters-tab" data-toggle="tab" href="#costCenters" role="tab"
                            aria-controls="costCenters" aria-selected="false">Centros de Costos</a>
                    </li>
                    <li class="nav-item" wire:ignore>
                        <a class="nav-link" id="users-tab" data-toggle="tab" href="#users" role="tab"
                            aria-controls="users" aria-selected="false">Usuarios</a>
                    </li>
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                        aria-expanded="false"><i class="fa-solid fa-bars" wire:ignore></i></a>
                    <div class="dropdown-menu" wire:ignore.self>
                        @if ($company->enabled)
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
                        <button type="button" class="dropdown-item" wire:confirm='¿Eliminar esta Empresa?'
                            wire:click='delete()'>
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
                                <strong>Descripción:</strong>
                                <p>{{ $company->description ?? 'Sin descripción.' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Fecha de Registro:</strong>
                                <p>{{ $company->created_at->format('d/m/Y h:i:s a') }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Última Actualización:</strong>
                                <p class="mb-0">{{ $company->updated_at->format('d/m/Y h:i:s a') }}</p>
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
                                    <x-adminlte-input fgroup-class="col-md-12" name="name" label="Nombre *"
                                        placeholder="Nombre de usuario" type="text" label-class="text-lightblue"
                                        wire:model.defer="name" required autocomplete="off" />
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

                <div class="tab-pane fade" id="costCenters" role="tabpanel" aria-labelledby="costCenters-tab"
                    wire:ignore.self>
                    <div class="card mb-0">
                        <div class="card-header">
                            <button class="btn btn-outline-primary btn-sm" type="button" data-toggle="collapse"
                                data-target="#ccForm" aria-expanded="false" aria-controls="ccForm">
                                <i class="fas fa-plus mr-1"></i>Nuevo Centro de Costos
                            </button>
                        </div>
                        <div id="ccForm" class="collapse row" wire:ignore.self>
                            <div class="col-md-12">
                                <div class="card">
                                    <form wire:submit.prevent="createCostCenter">
                                        <div class="card-body">
                                            <div class="row">
                                                <x-adminlte-input fgroup-class="col-md-12" name="ccName"
                                                    label="Nombre *" placeholder="Nombre de Centro de Costos"
                                                    type="text" label-class="text-lightblue"
                                                    wire:model.defer="ccName" required autocomplete="off" />
                                                <x-adminlte-textarea fgroup-class="col-md-12 mb-0"
                                                    name="ccDescription" label="Descripción" rows="3"
                                                    label-class="text-lightblue"
                                                    placeholder="Inserte una descripción..."
                                                    wire:model.defer="ccDescription" autocomplete="off">
                                                </x-adminlte-textarea>
                                            </div>
                                        </div>
                                        <div class="card-footer d-flex align-items-center justify-content-end">
                                            @if (session()->has('ccMessage'))
                                                <span class="text-success mr-3">{{ session('ccMessage') }}</span>
                                            @endif
                                            <button class="btn btn-outline-success btn-sm" type="submit">
                                                <i class="fas fa-lg fa-save mr-1" wire:loading.class="fa-spin"
                                                    wire:target="createCostCenter"></i>Guardar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="{{ config('styles.table') }}">
                                <thead class="{{ config('styles.table-thead') }}">
                                    <tr>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col" style="width: 50px">Ver</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($costCenters as $costCenter)
                                        <tr wire:key='costCenter-{{ $costCenter->id }}'
                                            @if (!$costCenter->enabled) class="text-danger" @endif>
                                            <td>{{ $costCenter->name }}</td>
                                            <td class="long-text">{{ $costCenter->description ?? 'Sin Descipción' }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.cost-centers.show', $costCenter->id) }}" target="_blank">
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

                        @if ($costCenters->hasPages())
                            <div class="card-footer pb-0">
                                {{ $costCenters->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab"
                    wire:ignore.self>
                    <div class="card mb-0">
                        <div class="card-header">
                            Usuarios
                        </div>
                        <div class="table-responsive">
                            <table class="{{ config('styles.table') }}">
                                <thead class="{{ config('styles.table-thead') }}">
                                    <tr>
                                        <th scope="col">id</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Email</th>
                                        <th scope="col" style="width: 50px">Ver</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr wire:key='user-{{ $user->id }}'
                                            @if (!$user->enabled) class="text-danger" @endif>
                                            <th scope="row">{{ $user->id }}</th>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <a href="{{ route('admin.users.show', $user->id) }}" target="_blank">
                                                    <i class="fa-solid fa-arrow-up-right-from-square text-info"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No se encontraron
                                                resultados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($users->hasPages())
                            <div class="card-footer pb-0">
                                {{ $users->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
