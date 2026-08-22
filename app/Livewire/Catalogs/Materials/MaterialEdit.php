<?php

namespace App\Livewire\Catalogs\Materials;

use App\Models\Catalogs\Material;
use App\Models\Catalogs\Unit;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class MaterialEdit extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $materialId;

    public string $name;
    public string $code;
    public string $description;
    public int $base_unit_id;
    public string $unitText;
    public bool $is_external;

    public function mount(int $materialId): void
    {
        $this->materialId  = $materialId;
        $material          = $this->material();

        $this->base_unit_id = $material->base_unit_id;
        $this->unitText      = $material->baseUnit->name;
        $this->name           = $material->name;
        $this->code            = $material->code ?? '';
        $this->description   = $material->description;
        $this->is_external    = $material->is_external;
    }

    public function render(): View
    {
        $units = Unit::active()
            ->get(['id', 'name', 'symbol']);

        return view('livewire.catalogs.materials.material-edit', [
            'units' => $units,
        ]);
    }

    public function save(): void
    {
        $material = $this->material();

        $validated = $this->validate([
            'name'         => ['required', 'string', 'max:64', Rule::unique('materials')->ignore($material->id)],
            'code'         => ['nullable', 'string', 'max:24', Rule::unique('materials')->ignore($material->id)],
            'description'  => ['nullable', 'string', 'max:255'],
            'base_unit_id' => ['required', 'integer', Rule::exists('units', 'id')->where('is_active', true)],
            'is_external'  => ['boolean'],
        ]);

        $material->update($validated);

        $this->toastSuccess('Información actualizada');
    }

    private ?Material $material = null;

    private function material(): Material
    {
        return $this->material ??= Material::findOrFail($this->materialId);
    }
}
