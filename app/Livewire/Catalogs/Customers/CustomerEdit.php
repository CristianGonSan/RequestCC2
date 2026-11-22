<?php

namespace App\Livewire\Catalogs\Customers;

use App\Models\Catalogs\Customer;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

use function view;

class CustomerEdit extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $customerId;

    public string $name;

    public function mount(int $customerId): void
    {
        $this->customerId = $customerId;
        $this->name = $this->customer()->name;
    }

    public function render(): View
    {
        return view('livewire.catalogs.customers.customer-edit');
    }

    public function save(): void
    {
        $customer = $this->customer();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:64', Rule::unique('customers')->ignore($customer->id)],
        ]);

        $customer->update($validated);

        $this->toastSuccess('Información actualizada');
    }

    private ?Customer $customer = null;

    private function customer(): Customer
    {
        return $this->customer ??= Customer::findOrFail($this->customerId);
    }
}
