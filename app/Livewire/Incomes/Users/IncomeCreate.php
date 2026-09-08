<?php

namespace App\Livewire\Incomes\Users;

use App\Models\Catalogs\Type;
use App\Models\Incomes\Income;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class IncomeCreate extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public ?int $copyFromId;

    public bool $createAnother = false;

    public string $concept = '';

    public int $company_id;

    public ?string $companyText = null;

    public int $type_id;

    public ?string $typeText = null;

    public string $payee = '';

    public string $amount = '';

    public string $income_date = '';

    public bool $is_transfer = false;

    public ?string $bank = null;

    public ?string $card = null;

    public ?string $account = null;

    public ?string $branch = null;

    public ?string $reference = null;

    public ?string $covenant = null;

    public function mount(?int $copyFromId = null): void
    {
        $this->copyFromId  = $copyFromId;
        $this->income_date = \now()->toDateString();

        if ($copyFromId !== null) {
            $this->loadCopy($copyFromId);
        }
    }

    public function render(): View
    {
        return view('livewire.incomes.users.incomes-create', [
            'typeOptions' => Type::optionsByAuth(),
        ]);
    }

    public function save(): void
    {
        // 1,000.00 => 1000.00
        $this->amount = str_replace(',', '', $this->amount ?? 0);

        $rules = [
            'concept'     => ['required', 'string', 'max:255'],
            'company_id'  => ['required', 'integer', Rule::exists('companies', 'id')->where('is_active', true)],
            'type_id'     => ['required', 'integer', Rule::exists('types', 'id')->where('is_active', true)],
            'payee'       => ['required', 'string', 'max:128'],
            'amount'      => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
            'income_date' => ['required', 'date'],
            'is_transfer' => ['required', 'boolean'],
        ];

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

            $this->card = \rtrim(\str_replace('_', '', $this->card), '-');

            if (\filled($this->account)) {
                $this->account = \rtrim(\str_replace('_', '', $this->account), '-');
            }
        }

        $validated            = $this->validate($rules);
        $validated['user_id'] = \auth()->id();

        $income = Income::create($validated);

        if ($this->createAnother) {
            $this->reset([
                'concept',
                'company_id',
                'type_id',
                'payee',
                'amount',
                'is_transfer',
                'bank',
                'card',
                'account',
                'branch',
                'reference',
                'covenant',
            ]);

            $this->income_date = \now()->toDateString();

            $this->dispatch('reset');
            $this->toastSuccess('Ingreso creado');
        } else {
            $this->flashToastSuccess('Ingreso creado');
            \redirect()->route('incomes.show', $income->id);
        }
    }

    private function loadCopy(int $copyFromId): void
    {
        $copyIncome = Income::with(['company:id,name,is_active', 'type:id,name,is_active'])->findOrFail($copyFromId);

        $company = $copyIncome->company;
        $type    = $copyIncome->type;

        $this->concept     = $copyIncome->concept;
        $this->company_id  = $company->id;
        $this->companyText = $company->name;
        $this->type_id     = $type->id;
        $this->typeText    = $type->name;
        $this->payee       = $copyIncome->payee;
        $this->amount      = (string) $copyIncome->amount;

        // La fecha no se copia: un ingreso nuevo siempre inicia con la fecha actual.
        $this->is_transfer = $copyIncome->is_transfer;

        $this->bank      = $copyIncome->bank;
        $this->card      = $copyIncome->card;
        $this->account   = $copyIncome->account;
        $this->branch    = $copyIncome->branch;
        $this->reference = $copyIncome->reference;
        $this->covenant  = $copyIncome->covenant;

        if (! $company->is_active) {
            $this->addError('company_id', 'La empresa copiada ya no está activa.');
        }

        if (! $type->is_active) {
            $this->addError('type_id', 'El tipo copiado ya no está activo.');
        }
    }
}
