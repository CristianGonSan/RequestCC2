<?php

namespace App\Livewire\Catalogs\Customers;

use App\Models\Catalogs\Customer;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

use function redirect;

class CustomerShow extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $customerId;

    public function mount(int $customerId): void
    {
        $this->customerId = $customerId;
    }

    public function render(): View
    {
        return view('livewire.catalogs.customers.customer-show', [
            'customer' => $this->customer(),
        ]);
    }

    public function toggleActive(): void
    {
        $this->toastSuccess(
            $this->customer()->toggleActive()
                ? 'Cliente activado'
                : 'Cliente desactivado'
        );
    }

    public function delete(): void
    {
        $customer = $this->customer();

        if ($customer->isInUse()) {
            $this->toastError('No se puede eliminar: el cliente está en uso');
        } else {
            $customer->delete();
            $this->flashToastSuccess('Cliente eliminado');
            redirect()->route('customers.index');
        }
    }

    private ?Customer $customer = null;

    private function customer(): Customer
    {
        return $this->customer ??= Customer::findOrFail($this->customerId);
    }
}
