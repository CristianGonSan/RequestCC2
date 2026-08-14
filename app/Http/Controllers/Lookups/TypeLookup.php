<?php

namespace App\Http\Controllers\Lookups;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TypeLookup extends Controller
{
    public function select2(Request $request): JsonResponse
    {
        $query = Type::query();

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

        $map = $results->map(fn (Type $item): array => [
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
        $userId = Auth::id();

        $query = Type::query();

        $query->whereHas('users', function ($q) use ($userId) {
            $q->where('type_user.user_id', $userId);
        });

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

        $results = $query->paginate(24, ['types.id', 'types.name']);

        $map = $results->map(fn (Type $item): array => [
            'id'    => $item->id,
            'text'  => $item->name,
        ]);

        return response()->json([
            'results'       => $map,
            'pagination'    => ['more' => $results->hasMorePages()],
        ]);
    }
}
