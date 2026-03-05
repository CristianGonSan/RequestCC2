<?php

namespace App\Livewire\Requests;

use App\Models\FileManagement;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Livewire\Attributes\On;

class ShowFiles extends Component
{
    use WithFileUploads;

    public $requestModel;
    public $file;

    public $fileName = 'Seleccionar archivo';

    public $files;

    public function mount($requestModel): void
    {
        $this->requestModel = $requestModel;
    }

    public function updatedFile(): void
    {
        $this->validate([
            'file' => 'required|file|mimes:pdf,jpeg,png,jpg,docx,doc,xlsx,xls|max:10240',
        ]);

        $this->fileName = $this->file?->getClientOriginalName() ?? 'Seleccionar archivo';
    }

    public function save(): void
    {
        $this->validate([
            'file' => 'required|file|mimes:pdf,jpeg,png,jpg,docx,doc,xlsx,xls|max:10240',
        ]);

        $path = 'requests/' . $this->requestModel->created_at->format('Y-m') . '/' . $this->requestModel->id;
        //$path = 'requests/' . $this->requestModel->id;
        $file_path = $this->file->store($path, 'local');

        if (Storage::exists($file_path)) {
            $fileManagement = new FileManagement();
            $fileManagement->request_id = $this->requestModel->id;
            $fileManagement->user_id = Auth::id();
            $fileManagement->file_path = $file_path;
            $fileManagement->original_name = $this->file->getClientOriginalName();
            $fileManagement->mime_type = $this->file->getMimeType();
            $fileManagement->size = $this->file->getSize();
            $fileManagement->save();

            $this->reset(['file', 'fileName']);
            $this->dispatch('fileSaved');
        } else {
            $this->dispatch('showError');
        }
    }

    public function render(): Application|Factory|View
    {
        $this->loanFiles();
        return view('livewire.requests.show-files');
    }

    public function loanFiles()
    {
        $this->files = $this->requestModel->files()
            ->orderByDesc('id')
            ->with('user')
            ->get();
    }

    public function downloadFile($fileId): BinaryFileResponse
    {
        $file = FileManagement::findOrFail($fileId);

        if (!Storage::disk('local')->exists($file->file_path)) {
            abort(404, 'Archivo no encontrado.');
        }

        return response()->download(Storage::path($file->file_path), $file->original_name);
    }

    #[On('deleteFile')]
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
        $this->dispatch('fileDeleted');
    }
}
