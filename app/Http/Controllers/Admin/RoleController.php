<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(): Application|Factory|View
    {
        return view('admin.roles.index');
    }

    public function create(): Application|Factory|View
    {
        $permissions = Permission::all();

        $permissionOptions = [];

        foreach ($permissions as $permission) {
            $permissionOptions[$permission->name] = $permission->name;
        }

        return view('admin.roles.create', compact('permissionOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        $role = Role::create($validated);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        if ($request->boolean('redirect_to_show')) {
            return redirect()->route('admin.roles.show', $role->id)
                ->with('success', 'Rol creado correctamente.');
        } else {
            return redirect()->route('admin.roles.index')
                ->with('success', 'Rol creado correctamente.');
        }
    }

    public function show(string $id): Application|Factory|View
    {
        $role = Role::findOrFail($id);
        return view('admin.roles.show', compact('role'));
    }
}
