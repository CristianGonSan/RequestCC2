<?php

namespace App\Http\Controllers\Catalogs;

use App\Models\Catalogs\Type;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class TypeController extends Controller
{
    public function index()
    {
        return view('catalogs.types.index');
    }

    public function show(int $id): View
    {
        abort_if(! Type::where('id', $id)->exists(), 404);

        return view('catalogs.types.show', [
            'typeId' => $id,
        ]);
    }

    public function create(): View
    {
        return view('catalogs.types.create');
    }

    public function edit(int $id): View
    {
        abort_if(! Type::where('id', $id)->exists(), 404);

        return view('catalogs.types.edit', [
            'typeId' => $id,
        ]);
    }
}
