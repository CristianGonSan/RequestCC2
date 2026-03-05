<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(): Application|Factory|View
    {
        return view('admin.permissions.index');
    }

    public function create(): Application|Factory|View
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        $permission = Permission::create($validated);

        if ($request->boolean('redirect_to_show')) {
            return redirect()->route('admin.permissions.show', $permission->id)
                ->with('success', 'Permiso creado correctamente.');
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permiso creado correctamente.');
    }

    public function show(string $id): View|Factory|Application
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.show', compact('permission'));
    }
}
