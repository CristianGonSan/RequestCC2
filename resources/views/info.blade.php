@use('App\Enums\Requests\MoneyRequestStatus')
@use('App\Enums\Requests\MaterialRequestStatus')

@extends('adminlte::page')

@section('title_prefix', 'Acerca del sistema |')

@section('css')
    <style>
        section {
            scroll-margin-top: 57px;
        }

        .sticky-top {
            top: 57px;
        }
    </style>
@endsection

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="m-0">Acerca del sistema</h1>
        <small class="text-muted">v{{ config('app.version') }}</small>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12 col-lg-9">
            <section id="description" class="py-3">
                <h5 class="font-weight-bold">SolicitudCC</h5>
                <p>
                    SolicitudCC centraliza la gestión de solicitudes de dinero y materiales, el registro de
                    ingresos y la consulta de saldos por centro de costo. También ofrece herramientas para
                    administrar catálogos, dar seguimiento a las solicitudes y consultar reportes.
                </p>
                <p class="mb-0">
                    Las solicitudes conservan información de su estado y seguimiento. Las funciones
                    disponibles para cada persona dependen de su cuenta, empresa y permisos asignados.
                </p>
            </section>

            <hr>

            <section id="areas" class="py-3">
                <h5 class="font-weight-bold">Áreas y módulos</h5>
                <p>
                    El sistema reúne las operaciones de usuarios, gestión y cumplimiento en módulos
                    relacionados:
                </p>

                <h6 class="font-weight-bold mt-3">Solicitudes de dinero</h6>
                <p>
                    Permite registrar solicitudes con concepto, importe, beneficiario, tipo, centro de costo
                    y método de pago, además de los datos bancarios que correspondan. Las personas
                    responsables pueden revisar y actualizar su estado; el área de contabilidad consulta las
                    solicitudes para registrar su pago.
                </p>

                <h6 class="font-weight-bold mt-3">Solicitudes de materiales</h6>
                <p>
                    Permite solicitar artículos del catálogo indicando cantidades y centro de costo. La
                    solicitud puede revisarse y aceptarse o rechazarse; el área de cumplimiento registra las
                    entregas y su avance hasta completar los artículos solicitados.
                </p>

                <h6 class="font-weight-bold mt-3">Ingresos</h6>
                <p>
                    Registro de ingresos con concepto, importe, fecha, tipo, centro de costo y forma de
                    recepción. También permite conservar los datos de pago relacionados y los archivos de
                    respaldo.
                </p>

                <h6 class="font-weight-bold mt-3">Balance y reportes</h6>
                <p>
                    Consulta de balances y resúmenes por periodo y centro de costo. Los informes y las
                    solicitudes o ingresos pueden exportarse cuando la cuenta tiene habilitado el permiso
                    correspondiente.
                </p>

                <h6 class="font-weight-bold mt-3">Catálogos</h6>
                <p>
                    Administración de empresas, centros de costo, tipos, unidades y materiales. Estos
                    catálogos proporcionan las opciones que se utilizan al registrar solicitudes e ingresos.
                </p>

                <h6 class="font-weight-bold mt-3">Administración y seguimiento</h6>
                <p class="mb-0">
                    Gestión de usuarios, roles y permisos, así como configuración de notificaciones. Las
                    solicitudes pueden incluir comentarios, archivos y un historial de cambios para facilitar
                    su seguimiento.
                </p>
            </section>

            <hr>

            <section id="flow" class="py-3">
                <h5 class="font-weight-bold">Estados de las solicitudes</h5>
                <p>
                    Los estados muestran el avance de cada solicitud. El flujo disponible depende de si se
                    trata de una solicitud de dinero o de materiales.
                </p>

                <h6 class="font-weight-bold mt-3">Solicitudes de dinero</h6>
                <dl class="row">
                    @foreach (MoneyRequestStatus::cases() as $status)
                        <dt class="col-sm-3">
                            <span class="badge badge-{{ $status->bootstrapColorClass() }}">{{ $status->label() }}</span>
                        </dt>
                        <dd class="col-sm-9">{{ $status->description() }}</dd>
                    @endforeach
                </dl>

                <h6 class="font-weight-bold mt-3">Solicitudes de materiales</h6>
                <dl class="row mb-0">
                    @foreach (MaterialRequestStatus::cases() as $status)
                        <dt class="col-sm-3">
                            <span class="badge badge-{{ $status->bootstrapColorClass() }}">{{ $status->label() }}</span>
                        </dt>
                        <dd class="col-sm-9">{{ $status->description() }}</dd>
                    @endforeach
                </dl>
            </section>

            <hr>

            <section id="ui" class="py-3">
                <h5 class="font-weight-bold">Uso de la interfaz</h5>

                <h6 class="font-weight-bold mt-3">Validación y seguimiento</h6>
                <p>
                    Los formularios muestran errores junto a los campos que requieren atención. Las vistas de
                    detalle reúnen los datos de la solicitud, su estado y, según el módulo y los permisos, sus
                    comentarios, archivos e historial.
                </p>

                <h6 class="font-weight-bold mt-3">Notificaciones</h6>
                <p>
                    Las notificaciones informan el resultado de una acción o indican que se necesita revisar
                    algún dato. Los botones siguientes muestran ejemplos visuales:
                </p>
                <div>
                    <button class="btn btn-outline-success mr-1" onclick="exampleSuccess()">
                        <i class="fas fa-fw fa-check-circle mr-1"></i>Éxito
                    </button>
                    <button class="btn btn-outline-warning mr-1" onclick="exampleWarning()">
                        <i class="fas fa-fw fa-exclamation-triangle mr-1"></i>Aviso
                    </button>
                    <button class="btn btn-outline-danger" onclick="exampleError()">
                        <i class="fas fa-fw fa-times-circle mr-1"></i>Error
                    </button>
                </div>

                <h6 class="font-weight-bold mt-3">Confirmaciones</h6>
                <p>
                    Las acciones que requieren confirmación muestran un diálogo para revisar la decisión
                    antes de continuar. Este ejemplo es demostrativo y no modifica ninguna solicitud.
                </p>
                <div>
                    <button class="btn btn-outline-primary" onclick="exampleConfirm()">
                        <i class="fas fa-fw fa-question-circle mr-1"></i>Ver confirmación
                    </button>
                </div>

                <h6 class="font-weight-bold mt-3">Permisos</h6>
                <p class="mb-0">
                    El acceso a funciones como la administración de usuarios, la gestión de solicitudes, la
                    contabilidad y las exportaciones está limitado por permisos. Si necesitas una función que
                    no aparece disponible, consulta con la persona administradora del sistema.
                </p>
            </section>

            <hr>

            <section id="stack" class="py-3">
                <h5 class="font-weight-bold">Stack tecnológico</h5>
                <p>El proyecto utiliza las siguientes tecnologías y paquetes:</p>
                <table class="table table-sm table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Tecnología</th>
                            <th>Versión declarada</th>
                            <th>Uso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a href="https://www.php.net" target="_blank" rel="noopener" class="link-subtle">PHP</a>
                            </td>
                            <td>8.3+</td>
                            <td>Lenguaje del servidor</td>
                        </tr>
                        <tr>
                            <td><a href="https://laravel.com" target="_blank" rel="noopener"
                                    class="link-subtle">Laravel</a></td>
                            <td>12.x</td>
                            <td>Framework de la aplicación</td>
                        </tr>
                        <tr>
                            <td><a href="https://livewire.laravel.com" target="_blank" rel="noopener"
                                    class="link-subtle">Livewire</a></td>
                            <td>3.x</td>
                            <td>Componentes interactivos de la interfaz</td>
                        </tr>
                        <tr>
                            <td><a href="https://jeroennoten.github.io/Laravel-AdminLTE" target="_blank" rel="noopener"
                                    class="link-subtle">Laravel-AdminLTE</a></td>
                            <td>3.x</td>
                            <td>Plantilla y componentes de administración</td>
                        </tr>
                        <tr>
                            <td><a href="https://spatie.be/docs/laravel-permission" target="_blank" rel="noopener"
                                    class="link-subtle">Spatie Laravel Permission</a></td>
                            <td>6.x</td>
                            <td>Roles y permisos</td>
                        </tr>
                        <tr>
                            <td><a href="https://spatie.be/docs/laravel-medialibrary" target="_blank" rel="noopener"
                                    class="link-subtle">Spatie Media Library</a></td>
                            <td>11.x</td>
                            <td>Archivos adjuntos y medios</td>
                        </tr>
                        <tr>
                            <td><a href="https://laravel-excel.com" target="_blank" rel="noopener"
                                    class="link-subtle">Maatwebsite Excel</a></td>
                            <td>3.x</td>
                            <td>Exportación de datos</td>
                        </tr>
                        <tr>
                            <td><a href="https://github.com/barryvdh/laravel-dompdf" target="_blank" rel="noopener"
                                    class="link-subtle">Laravel DomPDF</a></td>
                            <td>3.x</td>
                            <td>Generación de reportes PDF</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <hr>

            <section id="support" class="py-3">
                <h5 class="font-weight-bold">Soporte</h5>
                <p class="mb-0">
                    Para solicitar acceso, reportar un problema o pedir ayuda con una solicitud, contacta a la
                    persona administradora de SolicitudCC mediante los canales internos de tu organización.
                </p>
            </section>
        </div>

        <div class="col-lg-3 d-none d-lg-block">
            <nav class="sticky-top pt-3 pl-3 border-left">
                <p class="text-uppercase text-muted font-weight-bold" style="font-size:.75rem; letter-spacing:.05em">
                    En esta página
                </p>
                <ul class="list-unstyled">
                    <li class="mb-1"><a href="#description" class="text-secondary">Descripción general</a></li>
                    <li class="mb-1"><a href="#areas" class="text-secondary">Áreas y módulos</a></li>
                    <li class="mb-1"><a href="#flow" class="text-secondary">Estados de las solicitudes</a></li>
                    <li class="mb-1"><a href="#ui" class="text-secondary">Uso de la interfaz</a></li>
                    <li class="mb-1"><a href="#stack" class="text-secondary">Stack tecnológico</a></li>
                    <li class="mb-1"><a href="#support" class="text-secondary">Soporte</a></li>
                </ul>
            </nav>
        </div>
    </div>
@endsection

@section('footer')
    <div class="text-center">
        <strong>SolicitudCC</strong> &copy; {{ date('Y') }} &mdash; Gestión de solicitudes y recursos.
    </div>
@stop

@section('js')
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        function exampleSuccess() {
            Toast.fire({
                icon: 'success',
                title: 'Acción completada correctamente.'
            });
        }

        function exampleWarning() {
            Toast.fire({
                icon: 'warning',
                title: 'Revisa la información antes de continuar.'
            });
        }

        function exampleError() {
            Toast.fire({
                icon: 'error',
                title: 'No tienes permiso para esta acción.'
            });
        }

        function exampleConfirm() {
            Swal.fire({
                icon: 'question',
                title: '¿Deseas continuar?',
                text: 'Este ejemplo no modificará ninguna solicitud.',
                showCancelButton: true,
                confirmButtonText: 'Continuar',
                cancelButtonText: 'Cancelar'
            });
        }
    </script>
@endsection
