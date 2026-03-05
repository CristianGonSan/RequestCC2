<div class="card mb-0">
    <form wire:submit.prevent="save">
        <div class="card-header">
            Roles
        </div>
        <ul class="list-group list-group-flush overflow-auto" style="max-height: 400px;">
            @forelse ($roles as $role)
                <li class="list-group-item py-1" wire:key="role-{{ $role }}">
                    <div class="icheck-primary">
                        <input type="checkbox" id="role_{{ $role }}"
                            wire:model.defer='selectedRoles.{{ $role }}' />
                        <label for="role_{{ $role }}">
                            {{ $role }}
                        </label>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-muted">No hay datos.</li>
            @endforelse
        </ul>
        <div class="card-footer d-flex align-items-center justify-content-end">
            @if (session()->has('role_message'))
                <span class="text-success mr-3">{{ session('role_message') }}</span>
            @endif
            <button class="btn btn-outline-success btn-sm" type="submit">
                <i class="fas fa-lg fa-arrows-rotate mr-1" wire:loading.class="fa-spin"></i>Actualizar
            </button>
        </div>
    </form>
</div>
