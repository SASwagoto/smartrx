<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        $roles = Role::all();
        return view('backend.roles.index', compact('permissions', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
        ]);

        $role->syncPermissions($validated['permissions']);

        return redirect()
            ->route('roles.index')
            ->with('success', __('Role created successfully.'));
    }

    public function edit(Role $role)
    {
        return response()->json([
            'role' => $role,
            'rolePermissions' => $role->permissions()->pluck('name'),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role->update([
            'name' => $validated['name'],
        ]);

        $role->syncPermissions($validated['permissions']);

        return redirect()
            ->route('roles.index')
            ->with('success', __('Role updated successfully.'));
    }

    public function destroy(Role $role)
    {
        if($role->name == 'SuperAdmin') {
            return response()->json(['status' => false, 'message' => 'You cannot delete super admin.']);
        }

        $role->delete();

        return response()->json(['status' => true, 'message' => 'Role deleted successfully.'], 200);
    }
}
