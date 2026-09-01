<?php

namespace App\Livewire\MaterialRequests\Users;

use App\Enums\Requests\MaterialRequestStatus;
use App\Models\Catalogs\Material;
use App\Models\Catalogs\Type;
use App\Models\Catalogs\Unit;
use App\Models\MaterialRequests\MaterialRequest;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class MaterialRequestCreate extends Component
{
    use FlashToast, Toast;

    public string $concept = '';

    public int $cost_center_id;

    public ?string $costCenterText = null;

    public int $type_id;

    public ?string $typeText = null;

    public ?int $material_id = null;

    /**
     * @var array<string, array{material_id: int, material_name: string, unit_id: int|null, quantity_requested: string}>
     */
    public array $items = [];

    public function render(): View
    {
        return view('livewire.material-requests.users.material-request-create', [
            'typeOptions' => Type::optionsByAuth(),
            'unitOptions' => Unit::options(),
        ]);
    }

    public function save(): void
    {
        if (empty($this->items)) {
            $this->toastError('La solicitud debe tener por lo menos un material.');

            return;
        }

        $validated = $this->validate([
            'concept'                    => ['required', 'string', 'max:255'],
            'cost_center_id'             => ['required', 'integer', Rule::exists('cost_centers', 'id')->where('is_active', true)],
            'type_id'                    => ['required', 'integer', Rule::exists('types', 'id')->where('is_active', true)],
            'items'                      => ['required', 'array', 'min:1'],
            'items.*.material_id'        => ['required', 'integer', Rule::exists('materials', 'id')->where('is_active', true)],
            'items.*.unit_id'            => ['required', 'integer', Rule::exists('units', 'id')->where('is_active', true)],
            'items.*.quantity_requested' => ['required', 'numeric', 'min:0.001', 'max:999999999.999'],
        ]);

        $materialRequest = DB::transaction(function () use ($validated): MaterialRequest {
            $materialRequest = MaterialRequest::create([
                'user_id'        => auth()->id(),
                'cost_center_id' => $validated['cost_center_id'],
                'type_id'        => $validated['type_id'],
                'concept'        => $validated['concept'],
                'status'         => MaterialRequestStatus::Pending,
            ]);

            $materialRequest->items()->createMany(
                \array_map(
                    static fn (array $item): array => [
                        'material_id'        => $item['material_id'],
                        'unit_id'            => $item['unit_id'],
                        'quantity_requested' => $item['quantity_requested'],
                    ],
                    $validated['items'],
                ),
            );

            return $materialRequest;
        });

        $this->flashToastSuccess('Solicitud de materiales creada');

        redirect()->route('material-requests.show', $materialRequest->id);
    }

    public function addItem(): void
    {
        $this->resetErrorBag(['material_id']);

        if ($this->material_id === null) {
            $this->addError('material_id', 'Selecciona un material.');

            return;
        }

        $material = Material::find($this->material_id);

        if ($material === null) {
            $this->addError('material_id', 'El material seleccionado no es válido.');

            return;
        }

        $key = (string) Str::uuid();

        $this->items[$key] = [
            'material_id'        => $material->id,
            'material_name'      => $material->name,
            'unit_id'            => $material->base_unit_id,
            'quantity_requested' => '',
        ];
    }

    public function removeItem(string $key): void
    {
        unset($this->items[$key]);
    }
}
