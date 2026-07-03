<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount(['permissions', 'users'])
            ->orderBy('label')
            ->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:64', 'unique:roles,name', 'regex:/^[a-z_]+$/'],
            'label'       => ['required', 'string', 'max:128'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.regex' => 'Nama role hanya boleh huruf kecil dan garis bawah.',
        ]);

        Role::create($validated);

        return back()->with('success', "Role \"{$validated['label']}\" berhasil dibuat.");
    }

    public function destroy(Role $role)
    {
        if ($role->users()->exists()) {
            return back()->with('error', "Role \"{$role->label}\" masih dipakai oleh {$role->users()->count()} pengguna.");
        }

        $role->permissions()->detach();
        $role->delete();

        return back()->with('success', "Role \"{$role->label}\" berhasil dihapus.");
    }

    /** Halaman kelola hak akses sebuah role */
    public function editPermissions(Role $role)
    {
        // Kelompokkan permission per group (fitur), urut per action
        $permissions = Permission::orderBy('group')->orderBy('action')
            ->get()
            ->groupBy('group');

        $granted = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.permissions', compact('role', 'permissions', 'granted'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return back()->with('success', "Hak akses role \"{$role->label}\" berhasil diperbarui.");
    }
}
