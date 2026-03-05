<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CostCenter;
use Illuminate\Http\Request;

class CostCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.cost-centers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        $companiesOptions = [];

        foreach ($companies as $company) {
            $companiesOptions[$company->id] = $company->name;
        }

        return view('admin.cost-centers.create', compact('companiesOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255', 'unique:cost_centers,name'],
            'description' => 'nullable'
        ]);

        $cosstCenter = CostCenter::create($validated);

        if ($request->boolean('redirect_to_show')) {
            return redirect()->route('admin.cost-centers.show', $cosstCenter->id)
                ->with('success', 'Centro de Costos creado correctamente.');
        } else {
            return redirect()->route('admin.cost-centers.index')
                ->with('success', 'Centro de Costos creado correctamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $costCenter = CostCenter::with('company')->findOrFail($id);
        return view('admin.cost-centers.show', compact('costCenter'));
    }
}
