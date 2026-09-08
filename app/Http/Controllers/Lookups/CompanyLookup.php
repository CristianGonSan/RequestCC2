<?php

namespace App\Http\Controllers\Lookups;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyLookup extends Controller
{
    public function select2(Request $request): JsonResponse
    {
        $query = Company::query();

        if ($request->has('active')) {
            $request->boolean('active') ? $query->active() : $query->inactive();
        }

        if ($request->has('term')) {
            $term = $request->string('term');
            $query->where(
                fn ($q) => $q->where('name', 'like', "%$term%")
            );
        }

        $query->orderBy('name');

        $results = $query->paginate(24, ['id', 'name']);

        $map = $results->map(fn (Company $item): array => [
            'id'    => $item->id,
            'text'  => $item->name,
        ]);

        return response()->json([
            'results'       => $map,
            'pagination'    => ['more' => $results->hasMorePages()],
        ]);
    }

    public function select2ByAuthUser(Request $request): JsonResponse
    {
        $userId = auth()->id();

        $query = Company::where('companies.is_active', true);

        $query->join('company_user', function ($join) use ($userId): void {
            $join->on('company_user.company_id', '=', 'companies.id')
                ->where('company_user.user_id', '=', $userId);
        });

        if ($request->has('term')) {
            $term = $request->string('term');
            $query->where(
                fn ($q) => $q->where('name', 'like', "%$term%")
            );
        }

        $query->orderBy('name');

        $results = $query->paginate(24, ['companies.id', 'companies.name']);

        $map = $results->map(fn (Company $item): array => [
            'id'    => $item->id,
            'text'  => $item->name,
        ]);

        return response()->json([
            'results'       => $map,
            'pagination'    => ['more' => $results->hasMorePages()],
        ]);
    }
}
