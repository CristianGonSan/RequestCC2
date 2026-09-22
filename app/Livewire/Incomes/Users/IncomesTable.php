<?php

namespace App\Livewire\Incomes\Users;

use App\Exports\Excel\IncomesExport;
use App\Models\Catalogs\Type;
use App\Models\Incomes\Income;
use App\Support\DataBag;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Session;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class IncomesTable extends Component
{
    use HasLivewireTableBehavior, Toast;

    #[Session]
    public string $searchTerm = '';

    #[Session]
    public int $perPage = 12;

    #[Session]
    public int $page = 1;

    #[Session]
    public string $sortColumn = 'created_at';

    #[Session]
    public string $sortDirection = 'desc';

    #[Session]
    public array $filters = [
        'type'          => null,
        'payMethod'     => null,
        'minAmount'     => null,
        'maxAmount'     => null,
        'minDate'       => null,
        'maxDate'       => null,
        'onlyFavorites' => false,
    ];

    public function mount(): void
    {
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $incomes = $this->getQuery()->paginate($this->perPage);

        Auth::user()->attachFavoriteStatus($incomes);

        return view('livewire.incomes.users.incomes-table', [
            'incomes'     => $incomes,
            'typeOptions' => Type::options(),
        ]);
    }

    public function toggleOnlyFavoritesFilter(): void
    {
        $this->filters['onlyFavorites'] = ! $this->filters['onlyFavorites'];
        $this->setPage(1);
    }

    public function toggleFavorite(int $id): void
    {
        $moneyRequest = Income::findOrFail($id);
        $user         = Auth::user();

        $wasFavorited = $user->hasFavorited($moneyRequest);

        $user->toggleFavorite($moneyRequest);

        $this->toastSuccess($wasFavorited ? 'Favorito quitado' : 'Favorito añadido');
    }

    private function getQuery(): Builder
    {
        $query      = Income::query();
        $filtersBag = DataBag::make($this->filters);

        $query->with([
            'user:id,name',
            'costCenter:id,name,description,company_id',
            'costCenter.company:id,name',
            'type:id,name',
        ]);

        $query->join('users', 'incomes.user_id', '=', 'users.id')
            ->join('cost_centers', 'incomes.cost_center_id', '=', 'cost_centers.id')
            ->join('companies', 'cost_centers.company_id', '=', 'companies.id')
            ->join('types', 'incomes.type_id', '=', 'types.id')
            ->select('incomes.*');

        $query->when($filtersBag->filled('payMethod'),
            fn () => $query->where('incomes.is_transfer', $filtersBag->boolean('payMethod'))
        )
            ->when($filtersBag->boolean('onlyFavorites'), function ($query) {
                $query->whereHas('favoriters', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            })
            ->when($filtersBag->filled('type'),
                fn () => $query->where('incomes.type_id', $filtersBag->string('type'))
            )
            ->when($filtersBag->filled('minAmount'),
                fn () => $query->where('incomes.amount', '>=', $filtersBag->float('minAmount'))
            )
            ->when($filtersBag->filled('maxAmount'),
                fn () => $query->where('incomes.amount', '<=', $filtersBag->float('maxAmount'))
            )
            ->when($filtersBag->filled('minDate'),
                fn () => $query->where('incomes.income_date', '>=', $filtersBag->string('minDate'))
            )
            ->when($filtersBag->filled('maxDate'),
                fn () => $query->where('incomes.income_date', '<=', $filtersBag->string('maxDate'))
            );

        if ($term = $this->searchTerm) {
            if ($id = $this->getIdFromSearchTerm()) {
                $query->where('incomes.id', $id);
            } else {
                $query->where(function (Builder $query) use ($term): void {
                    $query->whereAny([
                        'users.name',
                        'cost_centers.name',
                        'companies.name',
                        'cost_centers.description',
                        'incomes.payee',
                        'types.name',
                        'incomes.concept',
                    ], 'like', "%$term%");
                });
            }
        }

        $sortable = [
            'created_at'  => 'incomes.created_at',
            'id'          => 'incomes.id',
            'payee'       => 'incomes.payee',
            'costCenter'  => 'cost_centers.name',
            'amount'      => 'incomes.amount',
            'type'        => 'types.name',
            'income_date' => 'incomes.income_date',
        ];

        $column = $sortable[$this->sortColumn] ?? 'incomes.created_at';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }

    public function deleteIncome(int $id): void
    {
        $income = Income::findOrFail($id);

        $income->delete();
        $this->toastSuccess('Ingreso eliminado correctamente.');
    }

    public function export(): ?BinaryFileResponse
    {
        $query = $this->getQuery()->forPage($this->page, $this->perPage);

        if (! $query->exists()) {
            $this->toastWarning('No hay nada para exportar');

            return null;
        }

        return Excel::download(new IncomesExport($query), 'Solicitudes.xlsx');
    }

    private function getIdFromSearchTerm(): ?int
    {
        if (\str_contains($this->searchTerm, ':id=')) {
            $data = \explode('=', $this->searchTerm);
            if (! empty($data[1])) {
                return (int) $data[1];
            }
        }

        return null;
    }
}
