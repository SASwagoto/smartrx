<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->get();

        // ২. রিকোয়েস্ট অনুযায়ী ডায়নামিক রোল মেমোরি Redis Cache লেয়ার থেকে আনা হলো
        $roles = Cache::remember('global_system_roles', now()->addDay(), function () {
            return Role::all(); // Collection of Role Objects
        });

        return view('backend.users.index', compact('users', 'roles'));
    }


    public function create()
    {
        $roles = Cache::remember('global_system_roles', now()->addDay(), function () {
            return Role::all();
        });

        return view('backend.users.create', compact('roles'));
    }
}
