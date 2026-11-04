<?php

namespace App\Livewire\Media;

use App\Enums\Files\FileExtensionGroups;
use App\Models\CustomMedia;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Session;
use Livewire\Component;

class MediaTable extends Component
{
    use HasLivewireTableBehavior;

    #[Session]
    public string $searchTerm = '';

    #[Session]
    public int $perPage = 24;

    #[Session]
    public int $page = 1;

    #[Session]
    public string $sortColumn = 'created_at';

    #[Session]
    public string $sortDirection = 'desc';

    #[Session]
    public array $filters = [
        'group'       => 'all',
        'onlyMyFiles' => false,
        'dateFrom'    => null,
        'dateTo'      => null,
    ];

    public function updatedSortColumn(): void
    {
        $this->afterSortChanged();
    }

    public function updatedSortDirection(): void
    {
        $this->afterSortChanged();
    }

    public function toggleOnlyMyFiles(): void
    {
        $this->filters['onlyMyFiles'] = ! $this->filters['onlyMyFiles'];
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.media.media-table', [
            'media'        => $this->getQuery()->paginate($this->perPage()),
            'groupOptions' => FileExtensionGroups::toSelectOptions(),
        ]);
    }

    private function getQuery(): Builder
    {
        $query = CustomMedia::query();

        $query->leftJoin('users', 'media.user_id', '=', 'users.id')
            ->select('media.*');

        $query->with('user:id,name');

        if ($this->filters['onlyMyFiles']) {
            $query->where('media.user_id', auth()->id());
        }

        if (filled($this->filters['group']) && ($group = FileExtensionGroups::tryFrom($this->filters['group']))) {
            $query->whereIn('media.mime_type', $group->mimes());
        }

        if (($term = trim($this->searchTerm)) !== '') {
            $query->where(function ($query) use ($term): void {
                $query->where('media.file_name', 'like', "%{$term}%")
                    ->orWhere('media.name', 'like', "%{$term}%")
                    ->orWhere('media.collection_name', 'like', "%{$term}%")
                    ->orWhere('media.model_type', 'like', "%{$term}%")
                    ->orWhere('media.model_id', 'like', "%{$term}%");

                if (! $this->filters['onlyMyFiles']) {
                    $query->orWhere('users.name', 'like', "%{$term}%");
                }
            });
        }

        if (filled($this->filters['dateFrom'])) {
            $query->whereDate('media.created_at', '>=', $this->filters['dateFrom']);
        }

        if (filled($this->filters['dateTo'])) {
            $query->whereDate('media.created_at', '<=', $this->filters['dateTo']);
        }

        $this->applySorting($query);

        return $query;
    }

    private function applySorting(Builder $query): void
    {
        $sortable = [
            'created_at' => 'media.created_at',
            'file_name'  => 'media.file_name',
            'size'       => 'media.size',
        ];

        $query->orderBy(
            $sortable[$this->sortColumn] ?? 'media.created_at',
            in_array($this->sortDirection, ['asc', 'desc'], true) ? $this->sortDirection : 'desc',
        );
    }

    private function perPage(): int
    {
        return \in_array($this->perPage, [12, 24, 48], true) ? $this->perPage : 24;
    }
}
