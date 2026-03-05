<div class="p-3">
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
    @endif

    <div class="mb-4">
        <p class="font-semibold text-lg">Cerrar sesiones en otros dispositivos</p>
        <p>Introduce tu contraseña para cerrar todas las sesiones activas en otros dispositivos.</p>
        <form wire:submit.prevent="logoutOtherDevices">
            <div class="form-group">
                <input type="email" name="email" class="d-none" autocomplete="username">
                <label for="currentPassword">Contraseña actual</label>
                <input type="password" id="currentPassword" class="form-control" wire:model="currentPassword" required
                    placeholder="Introduce tu contraseña"  autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-outline-danger mt-2">Cerrar otras sesiones</button>
        </form>
    </div>

    <div>
        <p class="font-semibold text-lg">Sesiones activas</p>

        @foreach ($sessions as $session)
            <div class="d-flex mb-3">
                <div class="text-muted text-center" style="min-width: 40px;">
                    <i class="{{ $session['icon'] }}"></i>
                </div>
                <div>
                    {{ $session['user_agent'] }}
                    <div>
                        <small class="mb-0 text-muted">{{ $session['ip_address'] }}</small>
                        <br>
                        @if ($session['is_current_device'])
                            <small class="text-success">Este dispositivo</small>
                        @else
                            <small class="text-muted">Última actividad: {{ $session['last_activity'] }}</small>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
