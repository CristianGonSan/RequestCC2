<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Company;

class CompanyController extends Controller
{
    public function index()
    {
        return view('catalogs.companies.index');
    }

    public function show(string $id)
    {
        abort_if(! Company::where('id', $id)->exists(), 404);

        return view('catalogs.companies.show', [
            'companyId' => $id,
        ]);
    }

    public function create()
    {
        return view('catalogs.companies.create');
    }

    public function edit(string $id)
    {
        abort_if(! Company::where('id', $id)->exists(), 404);

        return view('catalogs.companies.edit', [
            'companyId' => $id,
        ]);
    }
}
