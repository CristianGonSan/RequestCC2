@extends('adminlte::page')

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Información</li>
    </ol>
</nav>
<x-alert></x-alert>
@stop

@section('content')
<div class="">
    <h2>Instrucciones</h2>

    <!-- Índice de la página -->
    <div>
        <h4>Índice</h4>
        <ul>
            <li><a href="#crear-solicitud">Crear Nueva Solicitud</a></li>
            <li><a href="#opciones-pago">Opciones de Pago</a></li>
            <li><a href="#campos-obligatorios">Campos Obligatorios</a></li>
            <li><a href="#proceso-aceptacion">Proceso de Aceptación o Rechazo</a></li>
            <li><a href="#notificaciones-correo">Notificación por Correo</a></li>
        </ul>
    </div>

    <!-- Sección: Crear Nueva Solicitud -->
    <h3 id="crear-solicitud">Crear Nueva Solicitud</h3>
    <p>Para crear una nueva solicitud, sigue los siguientes pasos:</p>
    <ul>
        <li>Accede a la sección de <a href="{{ route('money-requests.create') }}">Nueva Solicitud</a>, en la barra
            lateral izquierda.</li>
        <li>Selecciona el método de pago: efectivo o transferencia.</li>
        <li>Completa todos los campos obligatorios marcados con un asterisco (*).</li>
        <li>Revisa los datos ingresados y haz clic en "Guardar".</li>
    </ul>

    <!-- Sección: Opciones de Pago -->
    <h3 id="opciones-pago">Opciones de Pago</h3>
    <p>Al crear una solicitud, puedes elegir entre las siguientes opciones de pago:</p>
    <ul>
        <li><strong>Efectivo:</strong> Selecciona esta opción si prefieres realizar el pago en efectivo.</li>
        <li><strong>Transferencia:</strong> Selecciona esta opción si prefieres realizar el pago mediante transferencia
            bancaria.</li>
    </ul>

    <!-- Sección: Campos Obligatorios -->
    <h3 id="campos-obligatorios">Campos Obligatorios</h3>
    <p>Asegúrate de completar todos los campos obligatorios marcados con un asterisco (*). Estos campos son esenciales
        para procesar tu solicitud correctamente.</p>

    <!-- Sección: Proceso de Aceptación o Rechazo -->
    <h3 id="proceso-aceptacion">Proceso de Aceptación o Rechazo</h3>
    <p>Una vez enviada la solicitud, seguirá el siguiente proceso:</p>
    <ul>
        <li>El encargado será notificado mediante un correo automatizado.</li>
        <li>La solicitud permanecerá en pendiente, hasta ser revisada por el equipo responsable.</li>
        <li>Recibirás una notificación por correo electrónico indicando si tu solicitud ha sido aceptada o rechazada.
        </li>
        <li>Si es rechazada, puede revisar los mensajes en la solicitud, el encargado pudo haber dejado el motivo.</li>
        <li>Mientras la solicitud esté <strong class="text-warning">Pendiente</strong>, puede ser editada o eliminada
            por el solicitante.</li>
    </ul>

    <!-- Sección: Notificación por Correo -->
    <h3 id="notificaciones-correo">Notificación por Correo</h3>
    <p>Recibirás notificaciones a tu correo electrónico registrado en las siguientes situaciones:</p>
    <ul>
        <li>Cualquier cambio de estado de la solicitud (Aceptada, Rechazada, Pagada, Cancelada, Pendiente).</li>
        <li>Solo si es una transferencia; si es en efectivo, se presupone la comunicación directa con el encargado.</li>
    </ul>
</div>
@stop