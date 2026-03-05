<div class="card mb-0" style="height: 70vh">
    <div class="card-header bg-dark d-flex">
        Archivos
        <div wire:loading class="ml-auto">
            <i class="fas fa-spinner fa-spin"> </i>
        </div>
    </div>
    <div class="card-body">
        @error('file')
            <div class="mb-3"><span class="text-danger">{{ $message }}</span></div>
        @enderror

        <p class="text-muted mb-3">
            Archivos admitidos: pdf, jpeg, png, jpg, docx, doc, xlsx, xls | Máximo 10MB.
        </p>

        <div wire:loading wire:target="file" class="mb-3 text-center text-muted">
            <i class="fas fa-spinner fa-spin mr-1"></i> Subiendo archivo...
        </div>

        <div style="height: 100%; overflow-y: auto;">
            @forelse($files as $file)
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded shadow-sm">
                    <div class="col-md-10 col-10">
                        <div class="text-truncate font-weight-bold">
                            {{ $file->user->name }}
                        </div>
                        <div class="text-truncate text-muted">
                            {{ $file->original_name }}
                        </div>
                    </div>
                    <div class="col-md-2 col-2 text-right">
                        <button type="button" class="btn btn-outline-info btn-sm" data-toggle="dropdown">
                            <i class="fa fa-bars"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{ route('file.preview', $file->id) }}" class="dropdown-item" title="Visualizar"
                                target="_blank">
                                <i class="fas fa-eye mr-1"></i> Visualizar
                            </a>
                            <button wire:click="downloadFile({{ $file->id }})" class="dropdown-item">
                                <i class="fas fa-download mr-1"></i> Descargar
                            </button>
                            @if ($file->user->id === Auth::id())
                                <button class="dropdown-item text-danger" onclick="deleteFile({{ $file->id }})">
                                    <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted">
                    No hay archivos aún.
                </div>
            @endforelse
        </div>
    </div>

    <div class="card-footer">
        <form wire:submit.prevent="save">
            <div class="input-group">
                <div class="custom-file">
                    <input wire:model="file" type="file" class="custom-file-input" id="customFile"
                        accept=".pdf, .jpeg, .png, .jpg, .docx, .doc, .xlsx, .xls" required>
                    <label class="custom-file-label" for="customFile">{{ $fileName }}</label>
                </div>
                <div class="input-group-append">
                    <button class="btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-upload mr-1"></i>Subir</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('js')
    <script>
        Livewire.on('showError', () => {
            Swal.fire({
                title: "¡Ups! Algo salió mal...",
                text: "Parece que el servidor no cooperó. Intenta nuevamente más tarde o contacta con soporte si el problema persiste.",
                icon: "error",
                confirmButtonColor: '#d33'
            });
        });

        Livewire.on('fileSaved', () => {
            Swal.fire({
                title: "Ok",
                text: "Archivo guardado exitosamente.",
                icon: "success"
            });
        });

        function deleteFile(id) {
            Swal.fire({
                title: '¿Está seguro de eliminar este archivo?',
                text: "¡Esta acción es irreversible! El archivo se eliminará permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteFile', {
                        id: id
                    });

                    Livewire.on('fileDeleted', () => {
                        Swal.fire({
                            title: "Archivo eliminado",
                            text: "El archivo ha sido eliminado correctamente.",
                            icon: "success",
                            confirmButtonColor: '#3085d6'
                        });
                    });
                }
            });
        }
    </script>
@endpush
