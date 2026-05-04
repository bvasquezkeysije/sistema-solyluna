<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->with('roles')
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }
}
