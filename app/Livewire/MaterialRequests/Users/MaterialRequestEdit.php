<?php

namespace App\Livewire\MaterialRequests\Users;

use App\Enums\Requests\ChargeType;
use App\Livewire\MaterialRequests\Users\Concerns\CalculatesMaterialRequestTotals;
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
    use FlashToast, Toast, CalculatesMaterialRequestTotals;

    #[Locked]
    public int $materialRequestId;

    public ?string $notes = null;

    public int $cost_center_id;

    public ?string $costCenterText = null;

    public int $customer_id;

    public ?string $customerText = null;

    public int $type_id;

    public ?string $typeText = null;

    public ?int $material_id = null;

    public ChargeType $charge_type = ChargeType::Iva;

    /**
     * @var array<string, array{material_id: int, material_name: string, unit_id: int|null, quantity_requested: string}>
     */
    public array $items = [];

    public function mount(int $materialRequestId): void
    {
        $this->materialRequestId = $materialRequestId;

        $materialRequest = $this->materialRequest();

        $costCenter = $materialRequest->costCenter;
        $customer   = $materialRequest->customer;
        $type       = $materialRequest->type;

        $this->notes          = $materialRequest->notes;
        $this->cost_center_id = $costCenter->id;
        $this->costCenterText = $costCenter->name;
        $this->customer_id    = $customer->id;
        $this->customerText   = $customer->name;
        $this->type_id        = $type->id;
        $this->typeText       = $type->name;
        $this->charge_type    = $materialRequest->charge_type;

        foreach ($materialRequest->items as $item) {
            $this->items[(string) $item->id] = [
                'material_id'        => $item->material_id,
                'material_name'      => $item->material->name,
                'material_code'      => $item->material->code,
                'unit_id'            => $item->unit_id,
                'quantity_requested' => (string) $item->quantity_requested,
                'unit_cost'          => (string) $item->unit_cost,
                'notes'              => $item->notes,
                'can_delete'         => $item->quantity_fulfilled == 0,
            ];
        }
    }

    public function render(): View
    {
        return view('livewire.material-requests.users.material-request-edit', [
            'materialRequest' => $this->materialRequest(),
            'typeOptions'     => Type::optionsByAuth(),
            'unitOptions'     => Unit::options(),
            'chargeDetails'   => ChargeType::details(),
            'totals'          => $this->calculateTotals($this->items, $this->charge_type),
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
            'notes'                      => ['nullable', 'string', 'max:255'],
            'charge_type'                => ['required', Rule::enum(ChargeType::class)],
            'items'                      => ['required', 'array', 'min:1'],
            'items.*.material_id'        => ['required', 'integer', Rule::exists('materials', 'id')->where('is_active', true)],
            'items.*.unit_id'            => ['required', 'integer', Rule::exists('units', 'id')->where('is_active', true)],
            'items.*.quantity_requested' => ['required', 'decimal:0,3', 'min:0.001', 'max:999999999.999'],
            'items.*.unit_cost'          => ['required', 'decimal:0,2', 'min:0', 'max:999999999999.99'],
            'items.*.notes'              => ['nullable', 'string', 'max:255'],
        ];

        if ($this->customer_id !== $materialRequest->customer_id) {
            $rules['customer_id'] = ['required', 'integer', Rule::exists('customers', 'id')->where('is_active', true)];
        }

        if ($this->cost_center_id !== $materialRequest->cost_center_id) {
            $rules['cost_center_id'] = ['required', 'integer', Rule::exists('cost_centers', 'id')->where('is_active', true)];
        }

        if ($this->type_id !== $materialRequest->type_id) {
            $rules['type_id'] = ['required', 'integer', Rule::exists('types', 'id')->where('is_active', true)];
        }

        $validated = $this->validate($rules);

        $idsToKeep   = $this->numericItemIds($validated['items']);
        $idsToDelete = $materialRequest->items()
            ->pluck('id')
            ->diff($idsToKeep)
            ->all();

        if ($materialRequest->items()
            ->whereIn('id', $idsToDelete)
            ->where('quantity_fulfilled', '>', 0)
            ->exists()
        ) {
            $this->toastError('No se puede eliminar: el material ya tiene cantidades suministradas.');

            return;
        }

        DB::transaction(function () use ($materialRequest, $validated): void {
            $chargeType   = $this->charge_type;
            $totals       = $this->calculateTotals($validated['items'], $chargeType);

            $materialRequest->update([
                'notes'          => $validated['notes'] ?? null,
                'cost_center_id' => $validated['cost_center_id'] ?? $materialRequest->cost_center_id,
                'customer_id'    => $validated['customer_id'] ?? $materialRequest->customer_id,
                'type_id'        => $validated['type_id'] ?? $materialRequest->type_id,
                'total'          => $totals['total'],
                'charge_type'    => $chargeType,
                'charge_rate'    => $chargeType->percentage(),
                'subtotal'       => $totals['subtotal'],
                'charge_amount'  => $totals['chargeAmount'],
            ]);

            $this->syncItems($materialRequest, $validated['items']);
        });

        $this->reloadState();
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
            'material_code'      => $material->code,
            'unit_id'            => $material->base_unit_id,
            'quantity_requested' => '',
            'unit_cost'          => '',
            'notes'              => '',
            'can_delete'         => true,
        ];
    }

    /**
     * @param  array<string|int, array<string, mixed>>  $items
     */
    private function syncItems(MaterialRequest $materialRequest, array $items): void
    {
        $idsToKeep   = $this->numericItemIds($items);
        $idsToDelete = $materialRequest->items()->pluck('id')->diff($idsToKeep)->all();

        if (! empty($idsToDelete)) {
            $materialRequest->items()->whereIn('id', $idsToDelete)->delete();
        }

        foreach ($items as $key => $item) {
            $data = [
                'material_id'        => $item['material_id'],
                'unit_id'            => $item['unit_id'],
                'quantity_requested' => $item['quantity_requested'],
                'unit_cost'          => $item['unit_cost'],
                'notes'              => $item['notes'] ?? null,
            ];

            if (\is_numeric($key)) {
                $materialRequest->items()->whereKey((int) $key)->update($data);
            } else {
                $materialRequest->items()->create($data);
            }
        }
    }

    /**
     * @param  array<string|int, mixed>  $items
     * @return array<int, int>
     */
    private function numericItemIds(array $items): array
    {
        return \array_values(\array_filter(
            \array_map(
                static fn (string|int $key): ?int => \is_numeric($key) ? (int) $key : null,
                \array_keys($items),
            ),
        ));
    }

    public function removeItem(string $key): void
    {
        if ($item = $this->items[$key] ?? null) {
            if (! $item['can_delete']) {
                $this->toastError('No se puede eliminar: el material ya tiene cantidades suministradas.');

                return;
            } else {
                unset($this->items[$key]);
            }
        }
    }

    private ?MaterialRequest $materialRequest = null;

    private function materialRequest(): MaterialRequest
    {
        return $this->materialRequest ??= MaterialRequest::with(['costCenter', 'customer', 'type', 'items.material'])
            ->findOrFail($this->materialRequestId);
    }

    private function reloadState(): void
    {
        $this->materialRequest = null;

        $materialRequest = $this->materialRequest();

        $this->notes          = $materialRequest->notes;
        $this->cost_center_id = $materialRequest->cost_center_id;
        $this->costCenterText = $materialRequest->costCenter->name;
        $this->customer_id    = $materialRequest->customer_id;
        $this->customerText   = $materialRequest->customer->name;
        $this->type_id        = $materialRequest->type_id;
        $this->typeText       = $materialRequest->type->name;
        $this->charge_type    = $materialRequest->charge_type;

        $this->items = [];

        foreach ($materialRequest->items as $item) {
            $this->items[(string) $item->id] = [
                'material_id'        => $item->material_id,
                'material_name'      => $item->material->name,
                'material_code'      => $item->material->code,
                'unit_id'            => $item->unit_id,
                'quantity_requested' => (string) $item->quantity_requested,
                'unit_cost'          => (string) $item->unit_cost,
                'notes'              => $item->notes,
                'can_delete'         => $item->quantity_fulfilled == 0,
            ];
        }
    }
}
