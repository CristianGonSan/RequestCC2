<?php

namespace App\Livewire\Common;

use App\Models\Common\ChangeLog;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use LogicException;

class ShowChangeLogs extends Component
{
    use WithPagination;

    #[Locked]
    public string $modelClass;

    #[Locked]
    public int $modelId;

    public function mount(string $modelClass, int $modelId): void
    {
        $this->modelClass = $modelClass;
        $this->modelId    = $modelId;
    }

    public function render(): View
    {
        $model = $this->subjectModel();

        $changeLogs = $model->morphMany(ChangeLog::class, 'subject')
            ->with('user:id,name,email')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate();

        return view('livewire.common.show-change-logs', [
            'changeLogs' => $changeLogs,
        ]);
    }

    private function subjectModel(): Model
    {
        if (! is_subclass_of($this->modelClass, Model::class)) {
            throw new LogicException("The [{$this->modelClass}] class must be an Eloquent model.");
        }

        $modelClass = $this->modelClass;

        return $modelClass::query()->findOrFail($this->modelId);
    }
}
