@extends('adminlte::page')

@section('plugins.Select2', true)
@section('plugins.iCheck', true)

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.companies.index') }}">Empresas</a></li>
            <li class="breadcrumb-item active">Nuevo</li>
        </ol>
    </nav>
    <x-alert></x-alert>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-sm-10">
            <form action="{{ route('admin.companies.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header">
                        Nueva Empresa
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Sección: Información personal -->
                            <h5 class="text-primary col-md-12">Información Basica</h5>
                            <x-adminlte-input fgroup-class="col-md-12" name="name" label="Nombre *"
                                placeholder="nombre de permiso" type="text" label-class="text-lightblue"
                                enable-old-support required />

                            <x-adminlte-textarea fgroup-class="col-md-12 mb-0" name="description" label="Descripción" rows=5
                                label-class="text-lightblue" placeholder="Inserte una descripción..."
                                enable-old-support>
                            </x-adminlte-textarea>
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
