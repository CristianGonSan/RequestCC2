<?php

namespace App\Livewire\Requests\Users;

use App\Models\Catalogs\Type;
use App\Models\RequestModel;
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
    public int $requestModelId;

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

    public function mount(int $requestModelId): void
    {
        $this->requestModelId = $requestModelId;

        $requestModel           = $this->requestModel();

        $costCenter             = $requestModel->costCenter;
        $type                   = $requestModel->type;

        $this->concept          = $requestModel->concept;
        $this->cost_center_id   = $costCenter->id;
        $this->costCenterText   = $costCenter->name;
        $this->type_id          = $type->id;
        $this->typeText         = $type->name;
        $this->payee            = $requestModel->payee;
        $this->amount           = (string) $requestModel->amount;

        $this->is_transfer      = $requestModel->is_transfer;

        $this->bank             = $requestModel->bank;
        $this->card             = $requestModel->card;
        $this->account          = $requestModel->account;
        $this->branch           = $requestModel->branch;
        $this->reference        = $requestModel->reference;
        $this->covenant         = $requestModel->covenant;
    }

    public function render(): View
    {
        return view('livewire.requests.users.requests-edit', [
            'requestModel'  => $this->requestModel(),
            'typeOptions'   => Type::optionsByAuth()
        ]);
    }

    public function save(): void
    {
        $requestModel = $this->requestModel();

        if (!$requestModel->status->isPending()) {
            $this->toastError('No se puede actualizar: la solicitud no esta en pendiente');

            return;
        }

        // 1,000.00 => 1000.00
        $this->amount = str_replace(',', '', $this->amount ?? 0);

        $rules = [
            'concept'           => ['required', 'string', 'max:255'],
            'payee'             => ['required', 'string', 'max:128'],
            'amount'            => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
        ];

        if ($this->cost_center_id !== $requestModel->cost_center_id) {
            $rules['cost_center_id'] = ['required', 'integer', Rule::exists('cost_centers', 'id')->where('is_active', true),];
        }

        if ($this->type_id !== $requestModel->type_id) {
            $rules['type_id'] = ['required', 'integer', Rule::exists('types', 'id')->where('is_active', true),];
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

        $requestModel->updateWithRecord($validated);

        $this->toastSuccess('Información actualizada');
    }

    private ?RequestModel $requestModel = null;

    private function requestModel(): RequestModel
    {
        return $this->requestModel ??= RequestModel::with(['costCenter', 'type'])->findOrFail($this->requestModelId);
    }
}
