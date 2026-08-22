<?php

namespace App\Livewire\Catalogs\Units;

use App\Models\Catalogs\Unit;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UnitEdit extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $unitId;

    public string $name;
    public string $symbol;

    public function mount(int $unitId): void
    {
        $this->unitId = $unitId;
        $unit         = $this->unit();

        $this->name   = $unit->name;
        $this->symbol = $unit->symbol;
    }

    public function render(): View
    {
        return view('livewire.catalogs.units.unit-edit');
    }

    public function save(): void
    {
        $unit = $this->unit();

        $validated = $this->validate([
            'name'   => ['required', 'string', 'max:64', Rule::unique('units')->ignore($unit->id)],
            'symbol' => ['required', 'string', 'max:16', Rule::unique('units')->ignore($unit->id)],
        ]);

        $unit->update($validated);

        $this->toastSuccess('Información actualizada');
    }

    private ?Unit $unit = null;

    private function unit(): Unit
    {
        return $this->unit ??= Unit::findOrFail($this->unitId);
    }
}
