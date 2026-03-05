@section('plugins.iCheck', true)

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
                <h5>{{ $user->name }} #{{ $user->id }}</h5>
                <p class="mb-0">{{ $user->email }}</p>
                @if ($user->enabled)
                    <span class="badge bg-success">Habilitado</span>
                @else
                    <span class="badge bg-danger">Deshabilitado</span>
                @endif
            </div>
            <div class="card-header" wire:ignore>
                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                            aria-controls="profile" aria-selected="true">Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="edit-tab" data-toggle="tab" href="#edit" role="tab"
                            aria-controls="edit" aria-selected="false">Editar</a>
                    </li>
                    <li class="nav-item" wire:ignore>
                        <a class="nav-link" id="requests-tab" data-toggle="tab" href="#requests" role="tab"
                            aria-controls="requests" aria-selected="false">Solicitudes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="type-tab" data-toggle="tab" href="#type" role="tab"
                            aria-controls="type" aria-selected="false">Tipos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="company-tab" data-toggle="tab" href="#company" role="tab"
                            aria-controls="company" aria-selected="false">Empresas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="role-tab" data-toggle="tab" href="#role" role="tab"
                            aria-controls="role" aria-selected="false">Roles</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="myTabContent" wire:ignore.self>
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab"
                    wire:ignore.self>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Solicitudes Pagadas:</strong>
                                <p>{{ $paidRequestCount }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Monto Pagado:</strong>
                                <p>${{ number_format($paidAmountSum, 2) }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Fecha de Registro:</strong>
                                <p>{{ $user->created_at->format('d/m/Y h:i:s a') }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Última Actualización:</strong>
                                <p class="mb-0">{{ $user->updated_at->format('d/m/Y h:i:s a') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex">
                        <div class="dropdown ml-auto">
                            <button type="button" class="btn btn-outline-info btn-sm dropdown-toggle"
                                data-toggle="dropdown">
                                <i class="fa-solid fa-bars mr-1"></i>Acciones
                            </button>

                            <div class="dropdown-menu">
                                @if ($user->enabled)
                                    <button type="button" class="dropdown-item" x-on:click="disableAccount">
                                        Deshabilitar cuenta
                                    </button>
                                @else
                                    <button type="button" class="dropdown-item" x-on:click="enableAccount">
                                        Habilitar cuenta
                                    </button>
                                @endif
                                <button type="button" class="dropdown-item" x-on:click="deleteAccount">
                                    Eliminar cuenta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab" wire:ignore.self>
                    <div class="card">
                        <form wire:submit.prevent="save">
                            <div class="card-header">
                                Editar datos
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <x-adminlte-input fgroup-class="col-md-12" name="name" label="Nombre"
                                        placeholder="Nombre de usuario" type="text" label-class="text-lightblue"
                                        wire:model.defer="name" />
                                    <x-adminlte-input fgroup-class="col-md-12 mb-0" name="email" label="Email"
                                        placeholder="ejemplo@gmail.com" type="text" label-class="text-lightblue"
                                        wire:model.defer="email" />
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
                    <div class="card mb-0">
                        <form wire:submit.prevent="changePassword">
                            <div class="card-header">
                                Cambiar Contraseña
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <x-adminlte-input fgroup-class="col-md-12" name="password"
                                        label="Nueva Contraseña" type="password" label-class="text-lightblue"
                                        required wire:model.defer="password" />
                                    <x-adminlte-input fgroup-class="col-md-12 mb-0" name="password_confirmation"
                                        label="Confirmar Contraseña" type="password" required
                                        label-class="text-lightblue" wire:model.defer="password_confirmation" />
                                </div>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-end">
                                @if (session()->has('password_message'))
                                    <span class="text-success mr-3">{{ session('password_message') }}</span>
                                @endif
                                <button class="btn btn-outline-success btn-sm" type="submit">
                                    <i class="fas fa-lg fa-arrows-rotate mr-1" wire:loading.class="fa-spin"
                                        wire:target="changePassword"></i>Actualizar
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
                        <div class="card-footer pb-0">
                            {{ $requests->links() }}
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="type" role="tabpanel" aria-labelledby="type-tab" wire:ignore>
                    <livewire:Admin.User.TypeUpdate :user="$user" lazy />
                </div>
                <div class="tab-pane fade" id="company" role="tabpanel" aria-labelledby="company-tab" wire:ignore>
                    <livewire:Admin.User.CompanyUpdate :user="$user" lazy />
                </div>
                <div class="tab-pane fade" id="role" role="tabpanel" aria-labelledby="role-tab" wire:ignore>
                    <livewire:Admin.User.RoleUpdate :user="$user" lazy />
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        function deleteAccount() {
            Swal.fire({
                title: '¿Está seguro de eliminar este usuario?',
                text: "¡Esta acción es irreversible! Se eliminarán permanentemente el usuario y todos sus registros. En su lugar, puede optar por deshabilitarlo.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteAccount');
                }
            });
        }

        function enableAccount() {
            Swal.fire({
                title: '¿Desea habilitar este usuario?',
                text: "El usuario podrá acceder nuevamente a su cuenta.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, habilitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('enableAccount');
                }
            });
        }

        function disableAccount() {
            Swal.fire({
                title: '¿Está seguro de deshabilitar este usuario?',
                text: "Podrá volver a habilitarlo en cualquier momento.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, deshabilitar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('disableAccount');
                }
            });
        }

        Livewire.on('accountEnabled', () => {
            Swal.fire({
                title: "Cuenta habilitada",
                text: "El usuario ya puede acceder nuevamente.",
                icon: "success",
                confirmButtonColor: '#3085d6'
            });
        });

        Livewire.on('accountDisabled', () => {
            Swal.fire({
                title: "Cuenta deshabilitada",
                text: "El usuario ya no podrá acceder.",
                icon: "success",
                confirmButtonColor: '#d33'
            });
        });

        Livewire.on('accountDeleted', () => {
            Swal.fire({
                title: "Cuenta eliminada",
                text: "El usuario y todos sus datos han sido eliminados permanentemente.",
                icon: "success",
                confirmButtonColor: '#d33'
            });
        });
    </script>
@endpush
