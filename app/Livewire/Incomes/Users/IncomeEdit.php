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

class IncomeEdit extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $incomeId;

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

    public function mount(int $incomeId): void
    {
        $this->incomeId = $incomeId;

        $income = $this->income();

        $company = $income->company;
        $type    = $income->type;

        $this->concept     = $income->concept;
        $this->company_id  = $company->id;
        $this->companyText = $company->name;
        $this->type_id     = $type->id;
        $this->typeText    = $type->name;
        $this->payee       = $income->payee;
        $this->amount      = (string) $income->amount;
        $this->income_date = $income->income_date->toDateString();

        $this->is_transfer = $income->is_transfer;

        $this->bank      = $income->bank;
        $this->card      = $income->card;
        $this->account   = $income->account;
        $this->branch    = $income->branch;
        $this->reference = $income->reference;
        $this->covenant  = $income->covenant;
    }

    public function render(): View
    {
        return view('livewire.incomes.users.incomes-edit', [
            'income'      => $this->income(),
            'typeOptions' => Type::optionsByAuth(),
        ]);
    }

    public function save(): void
    {
        $income = $this->income();

        // 1,000.00 => 1000.00
        $this->amount = str_replace(',', '', $this->amount ?? 0);

        $rules = [
            'concept'     => ['required', 'string', 'max:255'],
            'payee'       => ['required', 'string', 'max:128'],
            'amount'      => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
            'income_date' => ['required', 'date'],
        ];

        if ($this->company_id !== $income->company_id) {
            $rules['company_id'] = ['required', 'integer', Rule::exists('companies', 'id')->where('is_active', true)];
        }

        if ($this->type_id !== $income->type_id) {
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

            $this->card = \rtrim(\str_replace('_', '', $this->card), '-');

            if (\filled($this->account)) {
                $this->account = \rtrim(\str_replace('_', '', $this->account), '-');
            }
        }

        $validated = $this->validate($rules);

        $income->update($validated);

        $this->toastSuccess('Información actualizada');
    }

    private ?Income $income = null;

    private function income(): Income
    {
        return $this->income ??= Income::with(['company', 'type'])->findOrFail($this->incomeId);
    }
}
