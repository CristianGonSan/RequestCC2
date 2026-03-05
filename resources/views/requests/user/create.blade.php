@extends('adminlte::page')

@section('plugins.InputMask', true)
@section('plugins.Select2', true)

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('requests.index') }}">Mis Solicitudes</a></li>
            <li class="breadcrumb-item active">Nueva Solicitud</li>
        </ol>
    </nav>
    <x-alert></x-alert>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card" x-data="{ isTransfer: false }">
                <form action="{{ route('requests.store') }}" method="POST">
                    @csrf
                    <div class="card-header bg-dark">
                        <h5>Nueva Solicitud</h5>
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link" :class="{ 'active': !isTransfer }" x-on:click="isTransfer = false"
                                    href="#"><i class="fa-solid fa-money-bill mr-1"></i>Efectivo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" :class="{ 'active': isTransfer }" x-on:click="isTransfer = true"
                                    href="#"><i class="fa-solid fa-credit-card mr-1"></i>Transferencia</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <x-adminlte-textarea fgroup-class="col-md-12" name="concept" label="Concepto *" rows=3
                                label-class="text-warning" placeholder="Inserte el concepto..." enable-old-support
                                required></x-adminlte-textarea>

                            <x-adminlte-select fgroup-class="col-md-6" id="cost_center" name="cost_center"
                                label="Centro de Costos *" placeholder="centro de costos" label-class="text-lightblue"
                                enable-old-support required>
                                @foreach ($companies as $company)
                                    @foreach ($company->costCenters as $costCenter)
                                        <option value="{{ $costCenter->name }}" data-company="{{ $company->name }}"
                                            data-description="{{ $costCenter->description }}">
                                            {{ $costCenter->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </x-adminlte-select>

                            <x-adminlte-input fgroup-class="col-md-6" name="payee" label="Titular *" placeholder="titular"
                                label-class="text-lightblue" enable-old-support required />

                            <x-adminlte-input fgroup-class="col-md-6" name="amount" label="Monto *" placeholder="monto"
                                label-class="text-lightblue" enable-old-support required
                                data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" />

                            <x-adminlte-select2 fgroup-class="col-md-6" name="type" label="Tipo de Movimiento *"
                                placeholder="Selecciona el tipo" label-class="text-lightblue" enable-old-support required>
                                @foreach ($types as $type)
                                <option value="{{ $type->key }}">
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </x-adminlte-select2>
                        </div>

                        <div x-show="isTransfer" class="row">
                            <x-adminlte-input fgroup-class="col-md-4" name="bank" label="Banco *" placeholder="banco"
                                label-class="text-lightblue" enable-old-support required x-bind:disabled="!isTransfer" />
                            <x-adminlte-input fgroup-class="col-md-4" name="card" label="Tarjeta/CLABE *"
                                placeholder="tarjeta" label-class="text-lightblue" enable-old-support required
                                data-inputmask="'mask': '****-****-****-****[-****]', 'placeholder': '_'"
                                x-bind:disabled="!isTransfer" />
                            <x-adminlte-input fgroup-class="col-md-4" name="account" label="Cuenta" placeholder="cuenta"
                                label-class="text-lightblue" enable-old-support
                                data-inputmask="'mask': '****-****-****-****[-****]'" x-bind:disabled="!isTransfer" />
                            <x-adminlte-input fgroup-class="col-md-4" name="branch" label="Sucursal" placeholder="sucursal"
                                label-class="text-lightblue" enable-old-support x-bind:disabled="!isTransfer" />
                            <x-adminlte-input fgroup-class="col-md-4" name="reference" label="Referencia"
                                placeholder="referencia" label-class="text-lightblue" enable-old-support
                                x-bind:disabled="!isTransfer" />
                            <x-adminlte-input fgroup-class="col-md-4" name="covenant" label="Convenio"
                                placeholder="convenio" label-class="text-lightblue" enable-old-support
                                x-bind:disabled="!isTransfer" />
                        </div>
                        <input type="hidden" name="is_transfer" :value="isTransfer ? 1 : 0">
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-end">
                        <button class="btn btn-outline-success btn-sm" type="submit">
                            <i class="fas fa-lg fa-save mr-1"></i>Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $("input").inputmask({
                rightAlign: false
            });
            $('#cost_center').select2({
                theme: 'bootstrap4',
                templateResult: function(data) {
                    if (!data.id) return data.text;
                    var company = $(data.element).data('company');
                    var description = $(data.element).data('description');
                    return $(`
                        <div class="p-1">
                            <strong>${data.text}</strong>
                            <small class="d-block">${company}</small>
                            <small>${description}</small>
                        </div>
                    `);
                }
            });
        });
    </script>
@endsection
