<?php

namespace App\Livewire\Catalogs\Types;

use App\Models\Catalogs\Type;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class TypeShow extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $typeId;

    public function mount(int $typeId): void
    {
        $this->typeId = $typeId;
    }

    public function render(): View
    {
        return view('livewire.catalogs.types.type-show', [
            'type' => $this->type(),
        ]);
    }

    public function toggleActive(): void
    {
        $this->toastSuccess(
            $this->type()->toggleActive()
                ? 'Tipo activado'
                : 'Tipo desactivado'
        );
    }

    public function delete(): void
    {
        $type = $this->type();

        if ($type->isInUse()) {
            $this->toastError(
                'No se puede eliminar: el tipo está en uso'
            );
        } else {
            $type->delete();
            $this->flashToastSuccess('Tipo eliminado');
            redirect()->route('types.index');
        }
    }

    private ?Type $type = null;

    private function type(): Type
    {
        return $this->type ??= Type::findOrFail($this->typeId);
    }
}
