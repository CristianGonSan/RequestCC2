<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>{{ $user->name }} #{{ $user->id }}</h5>
                <p class="mb-0">{{ $user->email }}</p>
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
                    <li class="nav-item">
                        <a class="nav-link" id="role-tab" data-toggle="tab" href="#session" role="tab"
                            aria-controls="role" aria-selected="false">Sesiones</a>
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
                </div>
                <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab" wire:ignore.self>
                    <div>
                        <div class="card">
                            <form wire:submit.prevent="save">
                                <div class="card-header">
                                    Editar datos
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <x-adminlte-input fgroup-class="col-md-12" name="name" label="Nombre"
                                            placeholder="Nombre de usuario" type="text" label-class="text-lightblue"
                                            wire:model.defer="name" autocomplete="username" />
                                        <x-adminlte-input fgroup-class="col-md-12 mb-0" name="email" label="Email"
                                            placeholder="ejemplo@gmail.com" type="text" label-class="text-lightblue"
                                            wire:model.defer="email" autocomplete="username" />
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
                    <div class="card mb-0">
                        <form wire:submit.prevent="changePassword">
                            <div class="card-header">
                                Cambiar Contraseña
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <input type="email" name="email" class="d-none" autocomplete="username">

                                    <x-adminlte-input fgroup-class="col-md-12" name="current_password"
                                        label="Contraseña actual" type="password" autocomplete="current-password"
                                        label-class="text-lightblue" required wire:model.defer="current_password" />

                                    <x-adminlte-input fgroup-class="col-md-12" name="password"
                                        label="Nueva Contraseña" type="password" label-class="text-lightblue"
                                        required wire:model.defer="password" autocomplete="new-password" />

                                    <x-adminlte-input fgroup-class="col-md-12" name="password_confirmation"
                                        label="Confirmar Contraseña" type="password" required
                                        label-class="text-lightblue" wire:model.defer="password_confirmation"
                                        autocomplete="new-password" />

                                    <div class="col-md-12">
                                        <div class="card mb-0">
                                            <div class="p-2">
                                                <x-password-generator />
                                            </div>
                                        </div>
                                    </div>
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
                <div class="tab-pane fade" id="type" role="tabpanel" aria-labelledby="type-tab" wire:ignore>
                    <div class="card mb-0">
                        <div class="card-header">
                            Tipos
                        </div>
                        <ul class="list-group list-group-flush overflow-auto" style="max-height: 400px;">
                            @forelse ($types as $type)
                                <li class="list-group-item py-1" wire:key="type-{{ $type->id }}">
                                    {{ $type->name }}
                                </li>
                            @empty
                                <li class="list-group-item text-muted">Sin datos.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="tab-pane fade" id="company" role="tabpanel" aria-labelledby="company-tab" wire:ignore>
                    <div class="card mb-0">
                        <div class="card-header">
                            Empresas
                        </div>
                        <ul class="list-group list-group-flush overflow-auto" style="max-height: 400px;">
                            @forelse ($companies as $company)
                                <li class="list-group-item py-1" wire:key="company-{{ $company->id }}">
                                    {{ $company->name }}
                                </li>
                            @empty
                                <li class="list-group-item text-muted">Sin datos.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="tab-pane fade" id="role" role="tabpanel" aria-labelledby="role-tab" wire:ignore>
                    <div class="card mb-0">
                        <div class="card-header">
                            Roles
                        </div>
                        <ul class="list-group list-group-flush overflow-auto" style="max-height: 400px;">
                            @forelse ($roles as $role)
                                <li class="list-group-item py-1" wire:key="role-{{ $role }}">
                                    {{ $role }}
                                </li>
                            @empty
                                <li class="list-group-item text-muted">Sin datos.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="tab-pane fade" id="session" role="tabpanel" aria-labelledby="role-tab" wire:ignore>
                    <livewire:Account.ShowSessions>
                </div>
            </div>
        </div>
    </div>
</div>
