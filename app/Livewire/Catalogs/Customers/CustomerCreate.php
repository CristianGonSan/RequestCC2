<?php

namespace App\Livewire\Catalogs\Customers;

use App\Models\Catalogs\Customer;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

use function redirect;
use function view;

class CustomerCreate extends Component
{
    use FlashToast, Toast;

    public string $name = '';

    public bool $createAnother = false;

    public function render(): View
    {
        return view('livewire.catalogs.customers.customer-create');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:64', Rule::unique('customers')],
        ]);

        $customer = Customer::create($validated);

        if ($this->createAnother) {
            $this->reset('name');
            $this->toastSuccess('Cliente creado');
        } else {
            $this->flashToastSuccess('Cliente creado');
            redirect()->route('customers.show', $customer->id);
        }
    }
}
