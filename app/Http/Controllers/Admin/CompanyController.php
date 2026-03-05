<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.companies.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);

        $company = Company::create($validated);

        if ($request->boolean('redirect_to_show')) {
            return redirect()->route('admin.companies.show', $company->id)
                ->with('success', 'Empresa creada correctamente.');
        } else {
            return redirect()->route('admin.companies.index')
                ->with('success', 'Empresa creada correctamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = Company::findOrFail($id);
        return view('admin.companies.show', compact('company'));
    }
}
