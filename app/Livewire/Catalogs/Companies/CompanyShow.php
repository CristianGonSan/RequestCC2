<?php

namespace App\Livewire\Catalogs\Companies;

use App\Models\Catalogs\Company;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CompanyShow extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $companyId;

    public function mount(int $companyId): void
    {
        $this->companyId = $companyId;
    }

    public function render(): View
    {
        return view('livewire.catalogs.companies.company-show', [
            'company' => $this->company(),
        ]);
    }

    public function toggleActive(): void
    {
        $this->toastSuccess(
            $this->company()->toggleActive()
                ? 'Empresa activada'
                : 'Empresa desactivada'
        );
    }

    public function delete(): void
    {
        $company = $this->company();

        if ($company->isInUse()) {
            $this->toastError(
                'No se puede eliminar: la empresa está en uso'
            );
        } else {
            $company->delete();
            $this->flashToastSuccess('Empresa eliminada');
            redirect()->route('companies.index');
        }
    }

    private ?Company $company = null;

    private function company(): Company
    {
        return $this->company ??= Company::findOrFail($this->companyId);
    }
}
