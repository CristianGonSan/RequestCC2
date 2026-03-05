<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.types.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'key' => 'required|unique:types,key',
        ]);

        $type = Type::create($validated);

        if ($request->boolean('redirect_to_show')) {
            return redirect()->route('admin.types.show', $type->id)
                ->with('success', 'Tipo creado correctamente.');
        } else {
            return redirect()->route('admin.types.index')
                ->with('success', 'Tipo creado correctamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $type = Type::findOrFail($id);
        return view('admin.types.show', compact('type'));
    }
}
