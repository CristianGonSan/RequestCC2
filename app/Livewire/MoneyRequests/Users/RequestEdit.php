<?php

namespace App\Livewire\MoneyRequests\Users;

use App\Models\Catalogs\Type;
use App\Models\MoneyRequests\MoneyRequest;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RequestEdit extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $moneyRequestId;

    public string $concept = '';

    public int $cost_center_id;

    public ?string $costCenterText = null;

    public int $type_id;

    public ?string $typeText = null;

    public string $payee = '';

    public string $amount = '';

    public bool $is_transfer = false;

    public ?string $bank = null;

    public ?string $card = null;

    public ?string $account = null;

    public ?string $branch = null;

    public ?string $reference = null;

    public ?string $covenant = null;

    public function mount(int $moneyRequestId): void
    {
        $this->moneyRequestId = $moneyRequestId;

        $moneyRequest = $this->moneyRequest();

        $costCenter = $moneyRequest->costCenter;
        $type       = $moneyRequest->type;

        $this->concept        = $moneyRequest->concept;
        $this->cost_center_id = $costCenter->id;
        $this->costCenterText = $costCenter->name;
        $this->type_id        = $type->id;
        $this->typeText       = $type->name;
        $this->payee          = $moneyRequest->payee;
        $this->amount         = (string) $moneyRequest->amount;

        $this->is_transfer = $moneyRequest->is_transfer;

        $this->bank      = $moneyRequest->bank;
        $this->card      = $moneyRequest->card;
        $this->account   = $moneyRequest->account;
        $this->branch    = $moneyRequest->branch;
        $this->reference = $moneyRequest->reference;
        $this->covenant  = $moneyRequest->covenant;
    }

    public function render(): View
    {
        return view('livewire.money-requests.users.requests-edit', [
            'moneyRequest' => $this->moneyRequest(),
            'typeOptions'  => Type::optionsByAuth(),
        ]);
    }

    public function save(): void
    {
        $moneyRequest = $this->moneyRequest();

        if (! $moneyRequest->status->isPending()) {
            $this->toastError('No se puede actualizar: la solicitud no esta en pendiente');

            return;
        }

        // 1,000.00 => 1000.00
        $this->amount = str_replace(',', '', $this->amount ?? 0);

        $rules = [
            'concept' => ['required', 'string', 'max:255'],
            'payee'   => ['required', 'string', 'max:128'],
            'amount'  => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
        ];

        if ($this->cost_center_id !== $moneyRequest->cost_center_id) {
            $rules['cost_center_id'] = ['required', 'integer', Rule::exists('cost_centers', 'id')->where('is_active', true)];
        }

        if ($this->type_id !== $moneyRequest->type_id) {
            $rules['type_id'] = ['required', 'integer', Rule::exists('types', 'id')->where('is_active', true)];
        }

        if ($this->is_transfer) {
            $rules = [
                ...$rules,
                'bank'      => ['required', 'string', 'max:128'],
                'card'      => ['required', 'string', 'max:128'],
                'account'   => ['nullable', 'string', 'max:128'],
                'branch'    => ['nullable', 'string', 'max:128'],
                'reference' => ['nullable', 'string', 'max:128'],
                'covenant'  => ['nullable', 'string', 'max:128'],
            ];

            $this->card = rtrim(str_replace('_', '', $this->card), '-');

            if (filled($this->account)) {
                $this->account = rtrim(str_replace('_', '', $this->account), '-');
            }
        }

        $validated = $this->validate($rules);

        $moneyRequest->updateWithRecord($validated);

        $this->toastSuccess('Información actualizada');
    }

    private ?MoneyRequest $moneyRequest = null;

    private function moneyRequest(): MoneyRequest
    {
        return $this->moneyRequest ??= MoneyRequest::with(['costCenter', 'type'])->findOrFail($this->moneyRequestId);
    }
}
