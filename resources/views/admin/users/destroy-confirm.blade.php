@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.show', $user->id) }}">{{ $user->email }}</a></li>
            <li class="breadcrumb-item active">Confirmar Eliminación</li>
        </ol>
    </nav>
    <x-alert></x-alert>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <x-adminlte-card title="Confirmar Eliminación de Usuario" theme="danger">
                <h5 class="card-title">¡Atención!</h5>
                <p class="card-text">
                    Estás a punto de eliminar al usuario <strong>{{ $user->name }}</strong>. Esta acción es
                    <strong>irreversible</strong>.
                </p>
                <p class="card-text text-danger">
                    <strong>Advertencia:</strong> Eliminar este usuario resultará en la <strong>pérdida
                        permanente</strong> de todos los registros y datos relacionados. Esto incluye pero no se
                    limita a:
                </p>
                <ul>
                    <li>Datos personales</li>
                    <li>Historial de solicitudes</li>
                    <li>Archivos y documentos subidos</li>
                </ul>
                <p class="card-text">
                    Para preservar los datos, sugerimos deshabilitar al usuario, esto impedirá al usuario iniciar sesión
                    y usar su cuenta, sin afectar sus datos.
                </p>
                <p class="card-text">
                    Por favor, confirma si realmente deseas proceder con esta acción destructiva.
                </p>
                <x-slot name="footerSlot">
                    <div class="row">
                        <div class="col">
                            @if ($user->enabled)
                                <form action="{{ route('admin.users.disable', $user->id) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de querer deshabilitar a este usuario?')"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-user-slash mr-1"></i>Deshabilitar
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="col text-right">
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                onsubmit="return confirm('¿Lamentamos insistir, pero esta seguro de continuar?')"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fa-solid fa-user-xmark mr-1"></i>Eliminar
                                </button>
                            </form>
                        </div>

                    </div>
                </x-slot>
            </x-adminlte-card>
        </div>
    </div>
@endsection
