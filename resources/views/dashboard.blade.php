@extends('adminlte::page')

@section('content_header')
    <x-alert></x-alert>
@endsection

@section('content')
    <div class="row pt-3">
        <!-- Tarjeta de Solicitudes Totales -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                    <h5 class="text-muted mb-1">{{ $months['current']['name'] }}</h5>
                    <h3 class="text-dark font-weight-bold">{{ $months['current']['total'] }} Solicitudes</h3>
                    <h5 class="text-success font-weight-bold">{{ $months['current']['paid'] }} Pagadas</h5>
                    <h2 class="text-bold text-primary mt-2">{{ $months['current']['percentage'] }}%</h2>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Solicitudes Este Mes -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-2x text-warning mb-2"></i>
                    <h5 class="text-muted mb-1">{{ $months['previous']['name'] }}</h5>
                    <h3 class="text-dark font-weight-bold">{{ $months['previous']['total'] }} Solicitudes</h3>
                    <h5 class="text-success font-weight-bold">{{ $months['previous']['paid'] }} Pagadas</h5>
                    <h2 class="text-bold text-primary mt-2">{{ $months['previous']['percentage'] }}%</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-10 col-sm-12">
            <img src="{{ asset('img/cc.png') }}" alt="web" class="img-fluid rounded shadow-sm">
        </div>
    </div>
@endsection
