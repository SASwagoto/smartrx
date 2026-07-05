<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(UsersDataTable $datatable)
    {
        $roles = Role::all();
        return $datatable->render('backend.users.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole($request->role);

        Cache::forget('users');

        return response()->json(['success' => true, 'message' => 'User created successfully.'], 200);
    }

    public function edit(User $user)
    {
        $user->load('roles');
        $role = $user->roles->first();

        return response()->json([
            'user' => $user,
            'role' => $role->name,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->syncRoles([$request->role]);

        return response()->json(['success' => true, 'message' => 'User updated successfully.'], 200);
    }

    public function destroy(User $user)
    {
        if($user->id == auth()->id()) {
            return response()->json(['status' => false, 'message' => 'You cannot delete yourself.']);
        }
        if($user->hasRole('SuperAdmin')) {
            return response()->json(['status' => false, 'message' => 'You cannot delete super admin.']);
        }
        $user->delete();

        return response()->json(['status' => true, 'message' => 'User deleted successfully.'], 200);
    }
}
