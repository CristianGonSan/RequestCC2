@extends('adminlte::page')

@section('plugins.Chartjs', true)

@section('content_header')
    <x-alert></x-alert>
@endsection

@section('content')
    <div class="pt-3">
        <x-charts.paid-requests-weekly-chart></x-charts.paid-requests-weekly-chart>

        <x-charts.paid-requests-weekly-chart :onlyCurrentUser="true"></x-charts.paid-requests-weekly-chart>
    </div>

    <img src="{{ asset('img/cc.png') }}" alt="web" class="img-fluid rounded">
@endsection
