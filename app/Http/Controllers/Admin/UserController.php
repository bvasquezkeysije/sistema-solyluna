<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->q;
        $role = $request->role;
        $status = $request->status;

        $users = User::query()
            ->with('roles')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('username', 'like', '%'.$search.'%');
                });
            })
            ->when($role, function ($query) use ($role) {
                $query->whereHas('roles', function ($sub) use ($role) {
                    $sub->where('name', $role);
                });
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('is_active', $status === 'activo');
            })
            ->latest()
            ->paginate(10);

        $users->appends(request()->query());

        $roles = Role::query()->with('permissions')->orderBy('name')->get();
        $permissions = Permission::query()->orderBy('name')->get();

        return view('admin.users.index', compact('users', 'search', 'role', 'status', 'roles', 'permissions'));
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:roles,name'],
        ]);

        Role::create(['name' => $validated['name'], 'is_active' => true]);

        return back()
            ->with('roles_success', 'Rol creado correctamente.')
            ->with('open_roles_modal', true);
    }

    public function syncRolePermissions(Request $request)
    {
        $validated = $request->validate([
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'max:100'],
        ]);

        $role = Role::findOrFail($validated['role_id']);
        $role->syncPermissions($validated['permissions'] ?? []);

        return back()
            ->with('roles_success', 'Permisos del rol actualizados correctamente.')
            ->with('open_roles_modal', true);
    }

    public function updateRole(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:roles,name,'.$role->id],
        ]);

        $role->update(['name' => $validated['name']]);

        return back()
            ->with('roles_success', 'Rol actualizado correctamente.')
            ->with('open_roles_modal', true);
    }

    public function toggleRoleStatus(Role $role)
    {
        $role->update(['is_active' => !$role->is_active]);

        return back()
            ->with('roles_success', $role->is_active ? 'Rol activado correctamente.' : 'Rol desactivado correctamente.')
            ->with('open_roles_modal', true);
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'max:80', 'unique:users,username,'.$user->id],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,'.$user->id],
        ]);

        $user->update($validated);

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', $user->is_active ? 'Usuario activado correctamente.' : 'Usuario desactivado correctamente.');
    }
}
