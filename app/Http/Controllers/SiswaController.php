<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::with('classRoom')->get();
        $users = \App\Models\User::all();
        $roles = \App\Models\Role::all();

        return view('pages.members.index', [
            'title' => 'Kelola Data Siswa',
            'siswa' => $siswa,
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        $classes = ClassRoom::orderBy('name')->get();

        return view('pages.members.create.index', [
            'title' => 'Tambah Siswa',
            'classes' => $classes,
        ]);
    }

    public function edit(Siswa $siswa)
    {
        $classes = ClassRoom::orderBy('name')->get();

        return view('pages.members.edit.index', [
            'title' => 'Edit Siswa',
            'siswa' => $siswa,
            'classes' => $classes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:siswa,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'jurusan' => ['nullable', 'string', 'max:50'],
        ]);

// Generate ID siswa
        if (Schema::hasColumn('siswa', 'id_siswa')) {
            $validated['id_siswa'] = 'SIS' . str_pad((Siswa::count() + 1), 3, '0', STR_PAD_LEFT);
        }
        
        $validated['join_date'] = now()->toDateString();
        $validated['status'] = 'active';

        // Add nis if provided and column exists
        if ($request->filled('nis') && Schema::hasColumn('siswa', 'nis')) {
            $validated['nis'] = $request->nis;
        }

        Siswa::create($validated);

        return redirect()->route('members.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:siswa,email,' . $siswa->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'jurusan' => ['nullable', 'string', 'max:50'],
        ]);

        // Add nis if provided and column exists
        if ($request->filled('nis') && Schema::hasColumn('siswa', 'nis')) {
            $validated['nis'] = $request->nis;
        }

        $siswa->update($validated);

        return redirect()->route('members.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('members.index')->with('success', 'Siswa berhasil dihapus.');
    }

    public function profile()
    {
        $siswa = Siswa::where('email', auth()->user()->email)->first();

        return view('pages.member.profile', [
            'title' => 'Profil Siswa',
            'siswa' => $siswa,
        ]);
    }
}