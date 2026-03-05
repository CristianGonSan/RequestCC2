@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.cost-centers.index') }}">Centros de Costos</a></li>
            <li class="breadcrumb-item active">{{ $costCenter->name }}</li>
        </ol>
    </nav>
    <x-alert></x-alert>
@endsection

@section('content')
    <livewire:Admin.CostCenter.ShowCostCenter :costCenter="$costCenter" />
@endsection
