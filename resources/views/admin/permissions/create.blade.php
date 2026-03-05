@extends('adminlte::page')

@section('plugins.iCheck', true)

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Permisos</a></li>
            <li class="breadcrumb-item active">Nuevo</li>
        </ol>
    </nav>
    <x-alert></x-alert>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-sm-10">
            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header">
                        Nuevo Permiso
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Sección: Información personal -->
                            <h5 class="text-primary col-md-12">Información Basica</h5>
                            <x-adminlte-input fgroup-class="col-md-12 mb-0" name="name" label="Nombre *"
                                placeholder="nombre de permiso" type="text" label-class="text-lightblue"
                                enable-old-support required />
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        <button class="btn btn-outline-success btn-sm mr-3" type="submit">
                            <i class="fas fa-lg fa-save mr-1"></i>Guardar
                        </button>

                        <div class="icheck-primary">
                            <input type="checkbox" name="redirect_to_show" id="redirect_to_show">
                            <label for="redirect_to_show">
                                Visitar tras crear
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
