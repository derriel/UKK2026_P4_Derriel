<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::all();
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
        return view('pages.members.create.index', [
            'title' => 'Tambah Siswa',
        ]);
    }

    public function edit(Siswa $siswa)
    {
        return view('pages.members.edit.index', [
            'title' => 'Edit Siswa',
            'siswa' => $siswa,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:siswa,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'kelas' => ['nullable', 'string', 'max:10'],
            'jurusan' => ['nullable', 'string', 'max:50'],
            'nis' => ['nullable', 'string', 'max:50'],
        ]);

        // Generate ID siswa
        $validated['id_siswa'] = 'SIS' . str_pad((Siswa::count() + 1), 3, '0', STR_PAD_LEFT);
        $validated['join_date'] = now()->toDateString();
        $validated['status'] = 'active';

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
            'kelas' => ['nullable', 'string', 'max:10'],
            'jurusan' => ['nullable', 'string', 'max:50'],
            'nis' => ['nullable', 'string', 'max:50'],
        ]);

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
