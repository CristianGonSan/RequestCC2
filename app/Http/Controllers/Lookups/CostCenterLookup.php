<?php

namespace App\Http\Controllers\Lookups;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\CostCenter;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CostCenterLookup extends Controller
{
    public function select2(Request $request): JsonResponse
    {
        $query = CostCenter::query();

        $query->with(['company:id,name']);

        if ($request->has('active')) {
            $request->boolean('active') ? $query->active() : $query->inactive();
        }

        if ($request->has('term')) {
            $term = $request->string('term');
            $query->where(
                fn ($q) => $q
                    ->where('name', 'like', "$term%")
                    ->orWhere('description', 'like', "%$term%")
            );
        }

        $query->orderBy('name');

        $results = $query->paginate(24, ['id', 'name', 'description', 'company_id']);

        $map = $results->map(fn (CostCenter $item): array => [
            'id'            => $item->id,
            'text'          => $item->name,
            'company'       => $item->company?->name,
            'description'   => $item->description,
        ]);

        return response()->json([
            'results'       => $map,
            'pagination'    => ['more' => $results->hasMorePages()],
        ]);
    }

    public function select2ByAuthUser(Request $request): JsonResponse
    {
        $userId = Auth::id();

        $query = CostCenter::where('cost_centers.is_active', true);

        $query->with(['company:id,name']);

        $query->join('companies', 'companies.id', '=', 'cost_centers.company_id')
            ->join('company_user', function ($join) use ($userId): void {
                $join->on('company_user.company_id', '=', 'companies.id')
                    ->where('company_user.user_id', '=', $userId);
            })
            ->select([
                'cost_centers.*',
                'companies.name as company_name',
            ]);

        if ($request->has('term')) {
            $term = $request->string('term');
            $query->where(
                fn ($q) => $q
                    ->where('cost_centers.name', 'like', "$term%")
                    ->orWhere('cost_centers.description', 'like', "%$term%")
            );
        }

        $query->orderBy('cost_centers.name');

        $results = $query->paginate(24);

        $map = $results->map(fn (CostCenter $item): array => [
            'id'            => $item->id,
            'text'          => $item->name,
            'company'       => $item->company_name,
            'description'   => $item->description,
        ]);

        return response()->json([
            'results'       => $map,
            'pagination'    => ['more' => $results->hasMorePages()],
        ]);
    }
}
