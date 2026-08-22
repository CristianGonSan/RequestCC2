<?php

namespace App\Livewire\Catalogs\Materials;

use App\Models\Catalogs\Material;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class MaterialShow extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $materialId;

    public function mount(int $materialId): void
    {
        $this->materialId = $materialId;
    }

    public function render(): View
    {
        return view('livewire.catalogs.materials.material-show', [
            'material' => $this->material(),
        ]);
    }

    public function toggleActive(): void
    {
        $this->toastSuccess(
            $this->material()->toggleActive()
                ? 'Material activado'
                : 'Material desactivado'
        );
    }

    public function delete(): void
    {
        $material = $this->material();

        if ($material->isInUse()) {
            $this->toastError(
                'No se puede eliminar: el material está en uso'
            );
        } else {
            $material->delete();
            $this->flashToastSuccess('Material eliminado');
            redirect()->route('materials.index');
        }
    }

    private ?Material $material = null;

    private function material(): Material
    {
        return $this->material ??= Material::findOrFail($this->materialId);
    }
}
