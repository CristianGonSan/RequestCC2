<div>
    <style>
        th,
        td {
            text-align: center;
            /* Centrar horizontalmente */
            vertical-align: middle;
            /* Centrar verticalmente */
        }
    </style>

    <div class="mb-3" wire:ignore>
        <div class="row">
            <div class="col-md-11 col-10">
                <input type="text" id="search" class="form-control" wire:model.live.debounce.500ms="search"
                    placeholder="Buscar...">
            </div>

            <div class="col-md-1 col-2">
                <button class="btn btn-outline-primary btn-block px-0" type="button" data-toggle="collapse"
                    data-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </div>

        <div id="filtersCollapse" class="collapse row mt-3">
            <div class="col-md-4 col-12 mb-1">
                <select id="perPage" name="perPage" wire:model="perPage" wire:change="refreshPage" class="custom-select">
                    <option value="12">12 por página</option>
                    <option value="24">24 por página</option>
                    <option value="36">36 por página</option>
                    <option value="48">48 por página</option>
                    <option value="60">60 por página</option>
                </select>
            </div>
            <div class="col-md-4 col-6">
                <select id="orderBy" name="orderBy" wire:model="orderBy" wire:change="refreshPage"
                    class="custom-select">
                    <option value="created_at">Ordenar por Fecha</option>
                    <option value="name">Ordenar por Nombre</option>
                    <option value="id">Ordenar por ID</option>
                </select>
            </div>

            <!-- Dirección del orden -->
            <div class="col-md-4 col-6">
                <select id="orderDirection" name="orderDirection" wire:model="orderDirection" wire:change="refreshPage"
                    class="custom-select">
                    <option value="desc">Descendente</i></option>
                    <option value="asc">Ascendente</i></option>
                </select>
            </div>
        </div>

        <!-- Indicador de carga -->
        <div wire:loading class="col-md-12 mt-3">
            <div class="d-flex align-items-center justify-content-center">
                <div class="spinner-border text-primary mr-1" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
                <strong>Procesando su solicitud, por favor espere...</strong>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="table-responsive">
            <table class="{{ config('styles.table') }}">
                <thead class="{{ config('styles.table-thead') }}">
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col" style="min-width: 200px">Nombre</th>
                        <th scope="col" style="min-width: 100px">Creado el</th>
                        <th scope="col" style="min-width: 80px;">Ver más</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <th scope="row">{{ $role->id }}</th>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No se encontraron resultados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        {{ $roles->links() }}
    </div>
</div>
