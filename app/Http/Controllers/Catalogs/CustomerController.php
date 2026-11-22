<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\Customer;
use Illuminate\Contracts\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        return view('catalogs.customers.index');
    }

    public function show(string $id): View
    {
        abort_if(! Customer::where('id', $id)->exists(), 404);

        return view('catalogs.customers.show', [
            'customerId' => $id,
        ]);
    }

    public function create(): View
    {
        return view('catalogs.customers.create');
    }

    public function edit(string $id): View
    {
        abort_if(! Customer::where('id', $id)->exists(), 404);

        return view('catalogs.customers.edit', [
            'customerId' => $id,
        ]);
    }
}
