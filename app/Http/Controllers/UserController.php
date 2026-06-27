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
        return $datatable->render('backend.users.index');
    }


    public function create()
    {
        $roles = Cache::remember('global_system_roles', now()->addDay(), function () {
            return Role::all();
        });

        return view('backend.users.create', compact('roles'));
    }
}
