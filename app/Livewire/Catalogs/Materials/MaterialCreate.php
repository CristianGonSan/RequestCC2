<?php

namespace App\Livewire\Catalogs\Materials;

use App\Models\Catalogs\Material;
use App\Models\Catalogs\Unit;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class MaterialCreate extends Component
{
    use FlashToast, Toast;

    public string $name = '';

    public string $code = '';

    public string $description = '';

    public ?int $base_unit_id = null;

    public bool $is_external = false;

    public bool $createAnother = false;

    public function render(): View
    {
        return view('livewire.catalogs.materials.material-create', [
            'unitOptions' => Unit::options(),
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'         => ['required', 'string', 'max:64', Rule::unique('materials')],
            'code'         => ['nullable', 'string', 'max:24', Rule::unique('materials')],
            'description'  => ['nullable', 'string', 'max:255'],
            'base_unit_id' => ['required', 'integer', Rule::exists('units', 'id')->where('is_active', true)],
            'is_external'  => ['boolean'],
        ]);

        $material = Material::create($validated);

        if ($this->createAnother) {
            $this->reset([
                'name',
                'code',
                'description',
                'base_unit_id',
                'is_external',
            ]);

            $this->dispatch('reset');
            $this->toastSuccess('Material creado');
        } else {
            $this->flashToastSuccess('Material creado');
            redirect()->route('materials.show', $material->id);
        }
    }
}
