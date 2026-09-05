<?php

namespace App\Livewire\MoneyRequests;

use App\Models\MoneyRequests\FileManagement;
use App\Models\MoneyRequests\MoneyRequest;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ShowFiles extends Component
{
    use Toast, WithFileUploads;

    #[Locked]
    public int $moneyRequestId;

    public ?TemporaryUploadedFile $newFile = null;

    public string $fileName = 'Seleccionar archivo';

    public function mount(int $moneyRequestId): void
    {
        $this->moneyRequestId = $moneyRequestId;
    }

    public function render(): View
    {
        return view('livewire.money-requests.show-files', [
            'files' => $this->moneyRequest()->files()
                ->orderBy('id')
                ->with('user')
                ->get(),
        ]);
    }

    public function updatedNewFile(): void
    {
        $this->validate([
            'newFile' => ['required', 'file', 'mimes:pdf,jpeg,png,jpg,docx,doc,xlsx,xls', 'max:10240'],
        ]);

        $this->fileName = $this->newFile?->getClientOriginalName() ?? 'Seleccionar archivo';
    }

    public function save(): void
    {
        $this->validate([
            'newFile' => ['required', 'file', 'mimes:pdf,jpeg,png,jpg,docx,doc,xlsx,xls', 'max:10240'],
        ]);

        $moneyRequest = $this->moneyRequest();

        $path = 'requests/'.$moneyRequest->created_at->format('Y-m').'/'.$moneyRequest->id;

        $file_path = $this->newFile->store($path, 'local');

        if (Storage::exists($file_path)) {
            $fileManagement = new FileManagement();
            $fileManagement->request_id = $moneyRequest->id;
            $fileManagement->user_id = Auth::id();
            $fileManagement->file_path = $file_path;
            $fileManagement->original_name = $this->newFile->getClientOriginalName();
            $fileManagement->mime_type = $this->newFile->getMimeType();
            $fileManagement->size = $this->newFile->getSize();
            $fileManagement->save();

            $this->reset(['newFile', 'fileName']);
            $this->toastSuccess('Archivo subido correctamente.');
        } else {
            $this->toastError('Error al subir el archivo.');
        }
    }

    public function downloadFile($fileId): BinaryFileResponse
    {
        $file = FileManagement::findOrFail($fileId);

        if (! Storage::disk('local')->exists($file->file_path)) {
            abort(404, 'Archivo no encontrado.');
        }

        return response()->download(Storage::path($file->file_path), $file->original_name);
    }

    public function deleteFile($id): void
    {
        $file = FileManagement::findOrFail($id);

        if (Auth::id() !== $file->user_id) {
            abort(403, 'Acción no autorizada.');
        }

        if (Storage::disk('local')->exists($file->file_path)) {
            Storage::disk('local')->delete($file->file_path);
        }

        $file->delete();
        $this->toastSuccess('Archivo eliminado correctamente.');
    }

    private ?MoneyRequest $moneyRequest = null;

    private function moneyRequest(): MoneyRequest
    {
        return $this->moneyRequest ??= MoneyRequest::findOrFail($this->moneyRequestId);
    }
}
