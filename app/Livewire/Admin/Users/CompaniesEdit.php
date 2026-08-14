<?php

namespace App\Livewire\Admin\Users;

use App\Models\Company;
use App\Models\User;
use App\Traits\SweetAlert2\Livewire\Toast;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CompaniesEdit extends Component
{
    use Toast;

    #[Locked]
    public int $userId;

    public array $selectedCompanies = [];

    public function mount(int $userId): void
    {
        $this->userId = $userId;

        $this->selectedCompanies = $this->user()
            ->companies()
            ->pluck('company_id')
            ->mapWithKeys(fn ($id) => [$id => true])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.users.companies-edit', [
            'companies' => Company::active()->orderByDesc('id')->get(),
        ]);
    }

    public function save(): void
    {
        $this->user()->companies()->sync(array_keys($this->selectedCompanies));

        $this->toastSuccess('Empresas actualizadas.');
    }

    private ?User $user = null;

    private function user(): User
    {
        return $this->user ??= User::findOrFail($this->userId);
    }
}
