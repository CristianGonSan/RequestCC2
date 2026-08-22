<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Unit;
use Illuminate\Contracts\View\View;

class UnitController extends Controller
{
    public function index()
    {
        return view('catalogs.units.index');
    }

    public function show(int $id): View
    {
        abort_if(! Unit::where('id', $id)->exists(), 404);

        return view('catalogs.units.show', [
            'unitId' => $id,
        ]);
    }

    public function create(): View
    {
        return view('catalogs.units.create');
    }

    public function edit(int $id): View
    {
        abort_if(! Unit::where('id', $id)->exists(), 404);

        return view('catalogs.units.edit', [
            'unitId' => $id,
        ]);
    }
}
