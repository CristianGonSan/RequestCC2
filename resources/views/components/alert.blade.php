@if ($type)
    <div class="alert alert-{{ $type }} alert-dismissible fade show mt-3 mb-0 rounded shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <div class="mr-3">
                <!-- Ícono opcional con color acorde al tipo de alerta -->
                <i class="fas fa-info-circle fa-lg"></i>
            </div>
            <div>
                {{ $message }}
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mt-3 mb-0 rounded shadow-sm" role="alert">
        <h5 class="alert-heading font-weight-bold mb-2">Errores encontrados:</h5>
        <ul class="list-unstyled mb-0">
            @foreach ($errors->all() as $error)
                <li class="d-flex align-items-center mb-1">
                    <i class="fas fa-exclamation-triangle mr-2 text-danger"></i> {{ $error }}
                </li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
    </div>
@endif
