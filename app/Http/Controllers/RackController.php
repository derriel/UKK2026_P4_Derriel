<?php

namespace App\Http\Controllers;

use App\Models\Rack;
use App\Models\Category;
use Illuminate\Http\Request;

class RackController extends Controller
{
    public function index()
    {
        $racks = Rack::with('category')->get();

        return view('pages.racks.index', [
            'title' => 'Kelola Rak Buku',
            'racks' => $racks,
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        
        return view('pages.racks.create.index', [
            'title' => 'Tambah Rak',
            'categories' => $categories,
        ]);
    }

    public function edit(Rack $rack)
    {
        $categories = Category::all();
        
        return view('pages.racks.edit.index', [
            'title' => 'Edit Rak',
            'rack' => $rack,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
        ]);

        Rack::create($validated);

        return redirect()->route('racks.index')->with('success', 'Rak buku berhasil ditambahkan.');
    }

    public function update(Request $request, Rack $rack)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
        ]);

        $rack->update($validated);

        return redirect()->route('racks.index')->with('success', 'Rak buku berhasil diperbarui.');
    }

    public function destroy(Rack $rack)
    {
        $rack->delete();

        return redirect()->route('racks.index')->with('success', 'Rak buku berhasil dihapus.');
    }
}
