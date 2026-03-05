<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Configuración de Notificaciones por Correo</h5>
            </div>
            <div class="card-body">
                <!-- Notificación al crear una solicitud -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Al crearse una solicitud, notificar a:</label>
                    @foreach ($createRequest as $index => $email)
                        <x-adminlte-input type="email" fgroup-class="mb-1" name="createRequest.{{ $index }}"
                            placeholder="email@gmail.com" wire:model.defer="createRequest.{{ $index }}">
                            <x-slot name="appendSlot">
                                <button class="btn btn-outline-danger"
                                    wire:click="removeCreateEmail({{ $index }})">
                                    <i class="fas fa-trash" wire:loading.class="fa-spin"
                                        wire:target="removeCreateEmail"></i>
                                </button>
                            </x-slot>
                        </x-adminlte-input>
                    @endforeach
                    <form wire:submit.prevent="addCreateEmail">
                        <x-adminlte-input fgroup-class="mt-3" type="email" name="newCreateRequest"
                            wire:model.defer="newCreateRequest" placeholder="email@gmail.com"
                            placeholder="email@gmail.com" required>
                            <x-slot name="appendSlot">
                                <button class="btn btn-outline-success">
                                    <i class="fas fa-plus" wire:loading.class="fa-spin"
                                        wire:target="addCreateEmail"></i>
                                </button>
                            </x-slot>
                        </x-adminlte-input>
                    </form>
                </div>

                <!-- Notificación por cambio de estado -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Cuando una solicitud cambia de estado:</label>
                    @foreach ($statusOptions as $key => $name)
                        <div class="mb-2">
                            <strong>{{ $name }}</strong>
                            @foreach ($statusChange[$key] ?? [] as $index => $email)
                                <x-adminlte-input type="email" fgroup-class="mb-1"
                                    name="statusChange.{{ $key }}.{{ $index }}"
                                    placeholder="email@gmail.com"
                                    wire:model.defer="statusChange.{{ $key }}.{{ $index }}">
                                    <x-slot name="appendSlot">
                                        <button class="btn btn-outline-danger"
                                            wire:click="removeStatusChangeEmail('{{ $key }}', {{ $index }})">
                                            <i class="fas fa-trash" wire:loading.class="fa-spin"
                                                wire:target="removeStatusChangeEmail"></i>
                                        </button>
                                    </x-slot>
                                </x-adminlte-input>
                            @endforeach
                            <form wire:submit.prevent="addStatusChangeEmail('{{ $key }}')">
                                <x-adminlte-input type="email" fgroup-class="mt-3" name="newStatusChange.{{ $key }}"
                                    wire:model.defer="newStatusChange.{{ $key }}"
                                    placeholder="email@gmail.com" required>
                                    <x-slot name="appendSlot">
                                        <button class="btn btn-outline-success" type="submit">
                                            <i class="fas fa-plus" wire:loading.class="fa-spin"
                                                wire:target="addStatusChangeEmail"></i>
                                        </button>
                                    </x-slot>
                                </x-adminlte-input>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer d-flex">
                <button class="btn btn-outline-success btn-sm" wire:click="clearCache">
                    <i class="fas fa-broom mr-1" wire:loading.class="fa-spin" wire:target="clearCache"></i> Limpiar Caché
                </button>
                <button class="btn btn-outline-success btn-sm ml-auto" wire:click="save">
                    <i class="fas fa-save mr-1" wire:loading.class="fa-spin" wire:target="save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        Livewire.on('configurationUpdated', () => {
            Swal.fire({
                title: "Configuración Guarda",
                text: "La configuración de las notificaciones ha sido actualizada.",
                icon: "success",
                confirmButtonColor: '#3085d6'
            });
        });
        Livewire.on('cacheCleaned', () => {
            Swal.fire({
                title: "Caché Limpiada",
                text: "La Caché ha sido limpiada correctamente.",
                icon: "success",
                confirmButtonColor: '#3085d6'
            });
        });
        Livewire.on('duplicateMail', () => {
            Swal.fire({
                title: "Email duplicado.",
                text: "El Correo ya existe en la lista.",
                icon: "error",
                confirmButtonColor: '#3085d6'
            });
        });
    </script>
@endpush
