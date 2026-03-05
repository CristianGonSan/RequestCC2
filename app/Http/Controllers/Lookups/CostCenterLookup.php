<?php

namespace App\Http\Controllers\Lookups;

use App\Http\Controllers\Controller;
use App\Models\CostCenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CostCenterLookup extends Controller
{
    public function select2(Request $request): JsonResponse
    {
        $search = $request->input('search');

        $query = CostCenter::query()->with(['company']);

        $query->where('enabled', '=', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        $paginator = $query->orderBy('name')->paginate(15);

        $results = collect($paginator->items())->map(fn($item) => [
            'id'          => $item->id,
            'text'        => $item->name,
            'company'     => $item->company?->name,
            'description' => $item->description,
        ]);

        $json = [
            'results' => $results,
            'pagination' => [
                'more' => $paginator->hasMorePages(),
            ],
        ];

        return response()->json($json);
    }
}
