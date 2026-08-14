<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\CostCenter;

class CostCenterController extends Controller
{
    public function index()
    {
        return view('catalogs.cost-centers.index');
    }

    public function show(string $id)
    {
        abort_if(! CostCenter::where('id', $id)->exists(), 404);

        return view('catalogs.cost-centers.show', [
            'costCenterId' => $id,
        ]);
    }

    public function create()
    {
        return view('catalogs.cost-centers.create');
    }

    public function edit(string $id)
    {
        abort_if(! CostCenter::where('id', $id)->exists(), 404);

        return view('catalogs.cost-centers.edit', [
            'costCenterId' => $id,
        ]);
    }
}
