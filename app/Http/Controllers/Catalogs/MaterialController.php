<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Material;
use Illuminate\Contracts\View\View;

class MaterialController extends Controller
{
    public function index()
    {
        return view('catalogs.materials.index');
    }

    public function show(int $id): View
    {
        abort_if(! Material::where('id', $id)->exists(), 404);

        return view('catalogs.materials.show', [
            'materialId' => $id,
        ]);
    }

    public function create(): View
    {
        return view('catalogs.materials.create');
    }

    public function edit(int $id): View
    {
        abort_if(! Material::where('id', $id)->exists(), 404);

        return view('catalogs.materials.edit', [
            'materialId' => $id,
        ]);
    }
}
