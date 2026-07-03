<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $request->role));
        }

        $users = $query->paginate(15)->withQueryString();
        $roles  = Role::orderBy('label')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($request->filled('roles')) {
            $user->roles()->sync($request->roles);
        }

        return back()->with('success', "Pengguna {$user->name} berhasil ditambahkan.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->roles()->detach();
        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    /** Halaman untuk mengatur role seorang user */
    public function editRoles(User $user)
    {
        $roles      = Role::withCount('permissions')->orderBy('label')->get();
        $userRoles  = $user->roles->pluck('id')->toArray();

        return view('admin.users.roles', compact('user', 'roles', 'userRoles'));
    }

    public function updateRoles(Request $request, User $user)
    {
        $request->validate([
            'roles'   => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user->roles()->sync($request->roles ?? []);

        return back()->with('success', "Role {$user->name} berhasil diperbarui.");
    }
}
