@extends('adminlte::page')

@section('plugins.Select2', true)
@section('plugins.iCheck', true)

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item active">Nuevo</li>
        </ol>
    </nav>
    <x-alert></x-alert>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-sm-10">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <strong>Nuevo Usuario</strong>
                    </div>
                    <div class="card-body">

                        <div class="row border-bottom pb-3 mb-3">
                            <h5 class="text-primary col-md-12">Información Personal</h5>
                            <x-adminlte-input fgroup-class="col-md-6" name="name" label="Nombre *"
                                placeholder="Escribe el nombre completo" type="text" label-class="text-lightblue"
                                enable-old-support autocomplete="username" required />

                            <x-adminlte-input fgroup-class="col-md-6" name="email" label="Correo Electrónico *"
                                placeholder="ejemplo@gmail.com" type="email" label-class="text-lightblue"
                                enable-old-support autocomplete="username" required />
                        </div>

                        <div class="row border-bottom pb-3 mb-3">
                            <h5 class="text-primary col-md-12">Contraseña</h5>

                            <div class="col-md-6">
                                <x-adminlte-input class="show-p" name="password" label="Contraseña *"
                                    placeholder="Mínimo 8 caracteres" type="password" label-class="text-lightblue"
                                    enable-old-support autocomplete="new-password" required />
                                <small class="form-text text-muted">Debe contener letras y números.</small>
                            </div>

                            <div class="col-md-6">
                                <x-adminlte-input class="show-p" name="password_confirmation" label="Confirmar Contraseña *"
                                    placeholder="Repetir contraseña" type="password" label-class="text-lightblue"
                                    autocomplete="new-password" required />
                            </div>

                            <div class="col-md-12 mt-2">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="show_passwords">
                                    <label for="show_passwords">Mostrar contraseñas</label>
                                </div>
                            </div>

                            <div class="col-md-12 mt-2">
                                <div class="card">
                                    <div class="p-2">
                                        <x-password-generator />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="icheck-primary">
                                <input type="checkbox" name="send_email" id="send_email">
                                <label for="send_email">Enviar Email</label>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex align-items-center">
                        <button class="btn btn-outline-success btn-sm mr-3" type="submit">
                            <i class="fas fa-save mr-1"></i> Guardar
                        </button>

                        <div class="icheck-primary">
                            <input type="checkbox" name="redirect_to_show" id="redirect_to_show">
                            <label for="redirect_to_show">Visitar tras crear</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#show_passwords').change(function() {
                let type = $(this).is(':checked') ? 'text' : 'password';
                $('.show-p').attr('type', type);
            });
        });
    </script>
@endsection
