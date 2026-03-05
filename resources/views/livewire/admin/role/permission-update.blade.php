<div class="card mb-0">
    <form wire:submit.prevent="save">
        <div class="card-header">
            Permisos
        </div>
        <ul class="list-group list-group-flush overflow-auto" style="max-height: 400px;">
            @forelse ($permissions as $permission)
                <li class="list-group-item py-1" wire:key="permission-{{ $permission }}">
                    <div class="icheck-primary">
                        <input type="checkbox" id="permission_{{ $permission }}"
                            wire:model.defer='selectedPermissions.{{ $permission }}' />
                        <label for="permission_{{ $permission }}">
                            {{ $permission }}
                        </label>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-muted">Sin datos.</li>
            @endforelse
        </ul>
        <div class="card-footer d-flex align-items-center justify-content-end">
            @if (session()->has('permission_message'))
                <span class="text-success mr-3">{{ session('permission_message') }}</span>
            @endif
            <button class="btn btn-outline-success btn-sm" type="submit">
                <i class="fas fa-lg fa-arrows-rotate mr-1" wire:loading.class="fa-spin"></i>Actualizar
            </button>
        </div>
    </form>
</div>
