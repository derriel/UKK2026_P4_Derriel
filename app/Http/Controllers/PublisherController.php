<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublisherController extends Controller
{
    public function __construct()
    {
        $roleName = strtolower(optional(\Illuminate\Support\Facades\Auth::user()->role)->name ?? '');
        if (!in_array($roleName, ['admin', 'librarian'])) {
            abort(403);
        }
    }

    public function index()
    {
        $publishers = Publisher::all();

        return view('pages.publishers.index', [
            'title' => 'Kelola Data Penerbit',
            'publishers' => $publishers,
        ]);
    }

    public function create()
    {
        return view('pages.publishers.create.index', [
            'title' => 'Tambah Penerbit',
        ]);
    }

    public function edit(Publisher $publisher)
    {
        return view('pages.publishers.edit.index', [
            'title' => 'Edit Penerbit',
            'publisher' => $publisher,
        ]);
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:publishers,name,' . $publisher->id],
            'city' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            if ($publisher->logo && Storage::disk('public')->exists($publisher->logo)) {
                Storage::disk('public')->delete($publisher->logo);
            }
            $validated['logo'] = $request->file('logo')->store('publishers', 'public');
        }

        $publisher->update($validated);

        return redirect()->route('publishers.index')->with('success', 'Penerbit berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:publishers,name'],
            'city' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('publishers', 'public');
        }

        Publisher::create($validated);

        return redirect()->route('publishers.index')->with('success', 'Penerbit berhasil ditambahkan.');
    }

    public function destroy(Publisher $publisher)
    {
        $publisher->delete();

        return redirect()->route('publishers.index')->with('success', 'Penerbit berhasil dihapus.');
    }
}
