@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('requests.index') }}">Mis Solicitudes</a></li>
            <li class="breadcrumb-item active">Solicitud #{{ $requestModel->id }}</li>
        </ol>
    </nav>
    <x-alert></x-alert>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-sm-10">
            <ul class="nav nav-tabs" style="overflow-x: auto; overflow-y: hidden; flex-wrap: nowrap">
                <li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab">Detalles</a></li>
                <li class="nav-item"><a class="nav-link" href="#messages" data-toggle="tab">Mensajes</a></li>
                <li class="nav-item"><a class="nav-link" href="#files" data-toggle="tab">Archivos</a></li>
                <li class="nav-item"><a class="nav-link" href="#records" data-toggle="tab">Historial</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="details">
                    <div class="card">
                        <div class="card-header bg-dark">
                            Solicitud #{{ $requestModel->id }}
                        </div>
                        <div class="card-body">
                            @include('partials.requests.details')
                        </div>
                        <div class="card-footer">
                            <div class="btn-group">
                                <button class="btn btn-outline-info dropdown-toggle" type="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bars mr-1"></i> Acciones
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('requests.copy', $requestModel->id) }}">
                                        <i class="fas fa-copy mr-1"></i> Crear Copia
                                    </a>
                                    @if ($requestModel->isPending())
                                        <a class="dropdown-item" href="{{ route('requests.edit', $requestModel->id) }}">
                                            <i class="fas fa-edit mr-1"></i> Editar
                                        </a>
                                        <form action="{{ route('requests.destroy', $requestModel->id) }}" method="POST"
                                            class="d-inline w-100" id="deleteForm">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" class="dropdown-item text-danger" onclick="deleteRequest()">
                                                <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="messages">
                    <livewire:requests.ShowMessages :requestModel="$requestModel" />
                </div>
                <div class="tab-pane" id="files">
                    <livewire:requests.ShowFiles :requestModel="$requestModel" />
                </div>
                <div class="tab-pane" id="records">
                    <livewire:requests.ShowRecords :requestModel="$requestModel" />
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function deleteRequest() {
            Swal.fire({
                title: '¿Está seguro de eliminar esta solicitud?',
                text: "¡Esta acción es irreversible! Se eliminará permanentemente la solicitud.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#deleteForm').submit();
                }
            });
        }
    </script>
@endpush
