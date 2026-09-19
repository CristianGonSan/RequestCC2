@extends('adminlte::page')

@section('plugins.Chartjs', true)

@section('content_header')
    <x-alert></x-alert>
@endsection

@section('content')
    <div class="pt-3">
        <img src="{{ asset('img/logos/cc.png') }}" alt="web" class="img-fluid rounded p-5">
    </div>
@endsection
