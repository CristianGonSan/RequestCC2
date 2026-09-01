<?php

namespace App\Livewire\MaterialRequests\Users;

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
use Livewire\Attributes\Locked;
use Livewire\Component;

class MaterialRequestEdit extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $materialRequestId;

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

    public function mount(int $materialRequestId): void
    {
        $this->materialRequestId = $materialRequestId;

        $materialRequest = $this->materialRequest();

        $costCenter = $materialRequest->costCenter;
        $type       = $materialRequest->type;

        $this->concept        = $materialRequest->concept;
        $this->cost_center_id = $costCenter->id;
        $this->costCenterText = $costCenter->name;
        $this->type_id        = $type->id;
        $this->typeText       = $type->name;

        foreach ($materialRequest->items as $item) {
            $this->items[(string) $item->id] = [
                'material_id'        => $item->material_id,
                'material_name'      => $item->material->name,
                'unit_id'            => $item->unit_id,
                'quantity_requested' => (string) $item->quantity_requested,
            ];
        }
    }

    public function render(): View
    {
        return view('livewire.material-requests.users.material-request-edit', [
            'materialRequest' => $this->materialRequest(),
            'typeOptions'     => Type::optionsByAuth(),
            'unitOptions'     => Unit::options(),
        ]);
    }

    public function save(): void
    {
        $materialRequest = $this->materialRequest();

        if (! $materialRequest->status->isPending()) {
            $this->toastError('No se puede actualizar: la solicitud no está pendiente.');

            return;
        }

        if (empty($this->items)) {
            $this->toastError('La solicitud debe tener por lo menos un material.');

            return;
        }

        $rules = [
            'concept'                    => ['required', 'string', 'max:255'],
            'items'                      => ['required', 'array', 'min:1'],
            'items.*.material_id'        => ['required', 'integer', Rule::exists('materials', 'id')->where('is_active', true)],
            'items.*.unit_id'            => ['required', 'integer', Rule::exists('units', 'id')->where('is_active', true)],
            'items.*.quantity_requested' => ['required', 'numeric', 'min:0.001', 'max:999999999.999'],
        ];

        if ($this->cost_center_id !== $materialRequest->cost_center_id) {
            $rules['cost_center_id'] = ['required', 'integer', Rule::exists('cost_centers', 'id')->where('is_active', true)];
        }

        if ($this->type_id !== $materialRequest->type_id) {
            $rules['type_id'] = ['required', 'integer', Rule::exists('types', 'id')->where('is_active', true)];
        }

        $validated = $this->validate($rules);

        DB::transaction(function () use ($materialRequest, $validated): void {
            $materialRequest->update([
                'concept'        => $validated['concept'],
                'cost_center_id' => $validated['cost_center_id'] ?? $materialRequest->cost_center_id,
                'type_id'        => $validated['type_id'] ?? $materialRequest->type_id,
            ]);

            $existingIds = $materialRequest->items()->pluck('id')->all();

            $idsToKeep = \array_values(\array_filter(
                \array_map(
                    static fn (string $key): ?int => \is_numeric($key) ? (int) $key : null,
                    \array_keys($validated['items']),
                ),
            ));

            $idsToDelete = \array_diff($existingIds, $idsToKeep);

            if (! empty($idsToDelete)) {
                $materialRequest->items()->whereIn('id', $idsToDelete)->delete();
            }

            foreach ($validated['items'] as $key => $item) {
                $data = [
                    'material_id'        => $item['material_id'],
                    'unit_id'            => $item['unit_id'],
                    'quantity_requested' => $item['quantity_requested'],
                ];

                if (\is_numeric($key)) {
                    $materialRequest->items()->whereKey((int) $key)->update($data);
                } else {
                    $materialRequest->items()->create($data);
                }
            }
        });

        $this->toastSuccess('Solicitud de materiales actualizada');
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

    private ?MaterialRequest $materialRequest = null;

    private function materialRequest(): MaterialRequest
    {
        return $this->materialRequest ??= MaterialRequest::with(['costCenter', 'type', 'items.material'])
            ->findOrFail($this->materialRequestId);
    }
}
