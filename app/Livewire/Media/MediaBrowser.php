<?php

namespace App\Livewire\Media;

use App\Models\CustomMedia;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\HasMedia;

class MediaBrowser extends Component
{
    use Toast, WithFileUploads;

    #[Locked]
    public string $modelClass;

    #[Locked]
    public int $modelId;

    public string $collection = 'attachments';

    public string $acceptedMimes = 'pdf,jpeg,png,jpg,docx,doc,xlsx,xls';

    public int $maxSizeKb = 10240;

    public string $search = '';

    public ?TemporaryUploadedFile $upload = null;

    public string $fileName = 'Seleccionar archivo';

    public function mount(
        string $modelClass,
        int $modelId,
        string $collection = 'attachments',
        string $acceptedMimes = 'pdf,jpeg,png,jpg,docx,doc,xlsx,xls',
        int $maxSizeKb = 10240,
    ): void {
        $this->modelClass    = $modelClass;
        $this->modelId       = $modelId;
        $this->collection    = $collection;
        $this->acceptedMimes = $acceptedMimes;
        $this->maxSizeKb     = $maxSizeKb;
    }

    public function rules(): array
    {
        return [
            'upload' => ['required', 'file', "mimes:{$this->acceptedMimes}", "max:{$this->maxSizeKb}"],
        ];
    }

    public function updatedUpload(): void
    {
        $this->validate();

        $this->fileName = $this->upload->getClientOriginalName();
    }

    public function save(): void
    {
        $this->validate();

        $media = $this->resolveModel()
            ->addMedia($this->upload->getRealPath())
            ->usingFileName($this->upload->getClientOriginalName())
            ->toMediaCollection($this->collection, 'local');

        $media->update(['user_id' => auth()->id()]);

        $this->reset(['upload', 'fileName']);
        $this->toastSuccess('Archivo subido correctamente.');
    }

    public function delete(int $mediaId): void
    {
        $media = CustomMedia::findOrFail($mediaId);

        abort_unless($media !== null && $media->isOwnedBy(auth()->id()), 403);

        $media->delete();
        $this->toastSuccess('Archivo eliminado correctamente.');
    }

    public function render(): View
    {
        return view('livewire.media.media-browser', [
            'media' => $this->filteredMedia(),
        ]);
    }

    private function filteredMedia(): Collection
    {
        return $this->resolveModel()
            ->getMedia($this->collection)
            ->when($this->search !== '', fn (Collection $media): Collection => $media->filter(
                fn ($item): bool => str_contains(strtolower($item->file_name), strtolower($this->search))
            ))
            ->sortByDesc('created_at')
            ->values();
    }

    private function resolveModel(): HasMedia
    {
        return $this->modelClass::findOrFail($this->modelId);
    }
}
