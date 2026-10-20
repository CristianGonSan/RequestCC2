<?php

namespace App\Livewire\MoneyRequests;

use App\Enums\Common\ChangeLogEvent;
use App\Enums\Requests\MoneyRequestStatus;
use App\Models\Common\ChangeLog;
use App\Models\MoneyRequests\MoneyRequest;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class ShowChangeLogs extends Component
{
    use WithPagination;

    #[Locked]
    public int $moneyRequestId;

    public function mount(int $moneyRequestId): void
    {
        $this->moneyRequestId = $moneyRequestId;
    }

    public function render(): View
    {
        $moneyRequest = MoneyRequest::query()->findOrFail($this->moneyRequestId);
        $changeLogs   = $moneyRequest->changeLogs()
            ->with('user:id,name,email')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate();
        $statusChanges = $changeLogs->getCollection()
            ->filter(fn (ChangeLog $changeLog) => $changeLog->event === ChangeLogEvent::StatusChanged)
            ->mapWithKeys(fn (ChangeLog $changeLog) => [
                $changeLog->id => $this->statusPresentation($changeLog),
            ]);

        return view('livewire.money-requests.show-change-logs', [
            'changeLogs'    => $changeLogs,
            'statusChanges' => $statusChanges,
        ]);
    }

    /**
     * @return array{legacy: bool, text?: string, before?: string, beforeColor?: string, after?: string, afterColor?: string}
     */
    private function statusPresentation(ChangeLog $changeLog): array
    {
        $details = $changeLog->detailsBag();

        if ($details->has('legacy')) {
            return [
                'legacy' => true,
                'text'   => trim(strip_tags($details->string('legacy'))),
            ];
        }

        $statusFields = $details->array('fields')['status'] ?? [];
        $beforeValue  = $statusFields['before'] ?? null;
        $afterValue   = $statusFields['after'] ?? null;
        $beforeStatus = is_string($beforeValue) ? MoneyRequestStatus::tryFrom($beforeValue) : null;
        $afterStatus  = is_string($afterValue) ? MoneyRequestStatus::tryFrom($afterValue) : null;

        return [
            'legacy'      => false,
            'before'      => $beforeStatus?->label() ?? ChangeLog::formatValue($beforeValue),
            'beforeColor' => $beforeStatus?->bootstrapColorClass() ?? 'secondary',
            'after'       => $afterStatus?->label() ?? ChangeLog::formatValue($afterValue),
            'afterColor'  => $afterStatus?->bootstrapColorClass() ?? 'secondary',
        ];
    }
}
