<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class PetugasController extends Controller
{
    public function index()
    {
        $petugases = User::with('role')
            ->whereHas('role', function($query) {
                $query->where('name', 'petugas');
            })
            ->orderBy('name')
            ->get();

        $roles = Role::where('name', 'petugas')->orderBy('name')->get();

        return view('pages.petugases.index', [
            'title' => 'Kelola Data Petugas',
            'petugases' => $petugases,
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        $roles = Role::where('name', 'petugas')->orderBy('name')->get();
        return view('pages.petugases.create.index', [
            'title' => 'Tambah Petugas',
            'roles' => $roles,
        ]);
    }

    public function edit(User $petuga)
    {
        $roles = Role::where('name', 'petugas')->orderBy('name')->get();
        return view('pages.petugases.edit.index', [
            'title' => 'Edit Petugas',
            'petuga' => $petuga,
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

        return redirect()->route('petugases.index')->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function update(Request $request, User $petuga)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $petuga->id],
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
            if ($petuga->photo) {
                Storage::disk('public')->delete($petuga->photo);
            }
            $validated['photo'] = $request->file('photo')->store('users', 'public');
        }

        $petuga->update($validated);

        return redirect()->route('petugases.index')->with('success', 'Petugas berhasil diperbarui.');
    }

    public function destroy(User $petuga)
    {
        $petuga->delete();

        return redirect()->route('petugases.index')->with('success', 'Petugas berhasil dihapus.');
    }
}