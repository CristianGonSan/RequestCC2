@extends('adminlte::page')

@section('title_prefix', 'Contabilidad | ')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Contabilidad</li>
            </ol>
        </nav>

        <div style="height: 37.6px;" aria-hidden="true"></div>
    </div>
@endsection

@section('content')
    <livewire:MoneyRequests.Accounting.RequestsTable />
@endsection