<?php

namespace App\Http\Controllers\Lookups;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerLookup extends Controller
{
    public function select2(Request $request): JsonResponse
    {
        $query = Customer::query();

        if ($request->has('active')) {
            $request->boolean('active') ? $query->active() : $query->inactive();
        }

        if ($request->has('term')) {
            $term = $request->string('term');
            $query->where('name', 'like', "%$term%");
        }

        $query->orderBy('name');

        $results = $query->paginate(24, ['id', 'name']);

        $map = $results->map(fn (Customer $item): array => [
            'id'   => $item->id,
            'text' => $item->name,
        ]);

        return response()->json([
            'results'    => $map,
            'pagination' => ['more' => $results->hasMorePages()],
        ]);
    }
}
