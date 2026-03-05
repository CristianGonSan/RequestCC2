@if( session('success'))
    <x-adminlte-alert theme="success" title="¡Éxito!" dismissable>
        {{ session('success') }}
    </x-adminlte-alert>
@endif

@if ( $errors->any())
    <x-adminlte-alert theme="danger" title="¡Error!" dismissable>
        <ul>
            @foreach ( $errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-adminlte-alert>
@endif
