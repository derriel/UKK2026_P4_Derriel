<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    public function index()
    {
        $kelases = ClassRoom::all();

        return view('pages.kelas.index', [
            'title' => 'Kelola Kelas',
            'kelases' => $kelases,
        ]);
    }

    public function create()
    {
        return view('pages.kelas.create.index', [
            'title' => 'Tambah Kelas',
        ]);
    }

    public function edit(ClassRoom $kela)
    {
        return view('pages.kelas.edit.index', [
            'title' => 'Edit Kelas',
            'kela' => $kela,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'jurusan' => ['required', 'string', 'max:255'],
        ]);

        ClassRoom::create($validated);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, ClassRoom $kela)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'jurusan' => ['required', 'string', 'max:255'],
        ]);

        $kela->update($validated);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(ClassRoom $kela)
    {
        $kela->delete();

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}