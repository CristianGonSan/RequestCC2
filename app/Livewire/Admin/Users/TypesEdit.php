<?php

namespace App\Livewire\Admin\Users;

use App\Models\Catalogs\Type;
use App\Models\User;
use App\Traits\SweetAlert2\Livewire\Toast;
use Livewire\Attributes\Locked;
use Livewire\Component;

class TypesEdit extends Component
{
    use Toast;

    #[Locked]
    public int $userId;

    public array $selectedTypes = [];

    public function mount(int $userId): void
    {
        $this->userId = $userId;

        $this->selectedTypes = $this->user()
            ->types()
            ->pluck('type_id')
            ->mapWithKeys(fn ($id) => [$id => true])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.users.types-edit', [
            'types' => Type::active()->orderByDesc('id')->get(),
        ]);
    }

    public function save(): void
    {
        $this->user()->types()->sync(array_keys($this->selectedTypes));

        $this->toastSuccess('Tipos actualizados.');
    }

    private ?User $user = null;

    private function user(): User
    {
        return $this->user ??= User::findOrFail($this->userId);
    }
}
