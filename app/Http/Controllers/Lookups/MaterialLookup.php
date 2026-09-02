<?php

namespace App\Http\Controllers\Lookups;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Material;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaterialLookup extends Controller
{
    public function select2(Request $request): JsonResponse
    {
        $query = Material::query();

        if ($request->has('active')) {
            $request->boolean('active') ? $query->active() : $query->inactive();
        }

        if ($request->has('term')) {
            $term = $request->string('term');
            $query->where(
                fn ($q) => $q->where('name', 'like', "%$term%")
                ->orWhere('code', 'like', "%$term%")
            );
        }

        $query->orderBy('name');

        $results = $query->paginate(24, ['id', 'name', 'code', 'description', 'is_external']);

        $map = $results->map(fn (Material $item): array => [
            'id'          => $item->id,
            'text'        => $item->name,
            'code'        => $item->code,
            'description' => $item->description,
            'is_external' => $item->is_external,
        ]);

        return response()->json([
            'results'    => $map,
            'pagination' => ['more' => $results->hasMorePages()],
        ]);
    }
}
