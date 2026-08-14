<?php

namespace App\Livewire\Catalogs\CostCenters;

use App\Models\CostCenter;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CostCenterShow extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $costCenterId;

    public function mount(int $costCenterId): void
    {
        $this->costCenterId = $costCenterId;
    }

    public function render(): View
    {
        return view('livewire.catalogs.cost-centers.cost-center-show', [
            'costCenter' => $this->costCenter(),
        ]);
    }

    public function toggleActive(): void
    {
        $this->toastSuccess(
            $this->costCenter()->toggleActive()
                ? 'Centro de costos activado'
                : 'Centro de costos desactivado'
        );
    }

    public function delete(): void
    {
        $costCenter = $this->costCenter();

        if ($costCenter->isInUse()) {
            $this->toastError(
                'No se puede eliminar: el centro de costos está en uso'
            );
        } else {
            $costCenter->delete();
            $this->flashToastSuccess('Centro de costos eliminado');
            redirect()->route('cost-centers.index');
        }
    }

    private ?CostCenter $costCenter = null;

    private function costCenter(): CostCenter
    {
        return $this->costCenter ??= CostCenter::findOrFail($this->costCenterId);
    }
}
