<?php

namespace App\Traits\Livewire\Tables;

use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasFavorites
{
    use Toast;

    /**
     * Cada componente indica qué modelo maneja.
     * Ej: return MoneyRequest::class;
     */
    abstract protected function favoritableModel(): string;

    public function toggleOnlyFavoritesFilter(): void
    {
        $this->filters['onlyFavorites'] = ! $this->filters['onlyFavorites'];
        $this->setPage(1);
    }

    public function toggleFavorite(int $id): bool
    {
        $model = $this->favoritableModel()::findOrFail($id);
        $user  = Auth::user();

        $wasFavorited = $user->hasFavorited($model);

        $user->toggleFavorite($model);

        $this->toastSuccess($wasFavorited ? 'Favorito quitado' : 'Favorito añadido');

        return $wasFavorited;
    }

    /**
     * Aplica el filtro "solo favoritos" a cualquier query.
     */
    protected function applyFavoritesFilter(Builder $query): void
    {
        $query->whereHas('favoriters', fn ($q) => $q->where('user_id', Auth::id()));
    }

    protected function attachFavoriteStatus(&$favoriteables): void
    {
        Auth::user()->attachFavoriteStatus($favoriteables);
    }
}
