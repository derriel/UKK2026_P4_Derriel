<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::with('role')
            ->whereHas('role', function($query) {
                $query->where('name', 'admin');
            })
            ->orderBy('name')
            ->get();

        $roles = Role::where('name', 'admin')->orderBy('name')->get();

        return view('pages.admins.index', [
            'title' => 'Kelola Data Admin',
            'admins' => $admins,
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        $roles = Role::where('name', 'admin')->orderBy('name')->get();
        return view('pages.admins.create.index', [
            'title' => 'Tambah Admin',
            'roles' => $roles,
        ]);
    }

    public function edit(User $admin)
    {
        $roles = Role::where('name', 'admin')->orderBy('name')->get();
        return view('pages.admins.edit.index', [
            'title' => 'Edit Admin',
            'admin' => $admin,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['password'] = bcrypt($validated['password']);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('users', 'public');
        }

        User::create($validated);

        return redirect()->route('admins.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function update(Request $request, User $admin)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $admin->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('photo')) {
            if ($admin->photo) {
                Storage::disk('public')->delete($admin->photo);
            }
            $validated['photo'] = $request->file('photo')->store('users', 'public');
        }

        $admin->update($validated);

        return redirect()->route('admins.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(User $admin)
    {
        $admin->delete();

        return redirect()->route('admins.index')->with('success', 'Admin berhasil dihapus.');
    }
}