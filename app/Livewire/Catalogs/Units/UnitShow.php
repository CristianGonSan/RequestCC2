<?php

namespace App\Livewire\Catalogs\Units;

use App\Models\Catalogs\Unit;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UnitShow extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $unitId;

    public function mount(int $unitId): void
    {
        $this->unitId = $unitId;
    }

    public function render(): View
    {
        return view('livewire.catalogs.units.unit-show', [
            'unit' => $this->unit(),
        ]);
    }

    public function toggleActive(): void
    {
        $this->toastSuccess(
            $this->unit()->toggleActive()
                ? 'Unidad activada'
                : 'Unidad desactivada'
        );
    }

    public function delete(): void
    {
        $unit = $this->unit();

        if ($unit->isInUse()) {
            $this->toastError(
                'No se puede eliminar: la unidad está en uso'
            );
        } else {
            $unit->delete();
            $this->flashToastSuccess('Unidad eliminada');
            redirect()->route('units.index');
        }
    }

    private ?Unit $unit = null;

    private function unit(): Unit
    {
        return $this->unit ??= Unit::findOrFail($this->unitId);
    }
}
