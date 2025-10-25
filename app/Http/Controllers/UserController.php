<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage users')
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }

    /** 🧾 Tampilkan semua user */
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    /** ➕ Form tambah user */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /** 💾 Simpan user baru */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'position' => $request->position,
            'active'   => $request->active ?? 1,
            'password' => Hash::make($request->password),
        ]);

        // ✅ Simpan role
        if ($request->has('roles')) {
            $user->assignRole($request->roles);
        }

        // ✅ Simpan permission manual (opsional)
        if ($request->has('permissions')) {
            $user->givePermissionTo($request->permissions);
        }

        activity('user')->causedBy(Auth::user())->performedOn($user)
            ->withProperties(['email' => $user->email])
            ->log('Menambahkan user baru');

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }


    /** ✏️ Form edit user */
    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    /** 🔄 Update user */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->update($request->only('name', 'email', 'phone', 'position', 'active'));

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // ✅ Reset Role & Assign Role Baru
        $user->syncRoles($request->roles ?? []);

        // ✅ Reset Permission & Assign Baru jika ada
        $user->syncPermissions($request->permissions ?? []);

        activity('user')->causedBy(Auth::user())->performedOn($user)
            ->withProperties(['email' => $user->email])
            ->log('Mengubah user');

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }
    /** ❌ Hapus user */
    public function destroy(User $user)
    {
        // ✅ FIX: hapus ->activity()
        activity('user')
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->withProperties(['email' => $user->email])
            ->log('Menghapus user');

        $user->delete();

        return back()->with('success', 'User berhasil dihapus!');
    }
}
