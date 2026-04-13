<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publishers = Publisher::all();

        return view('pages.publishers.index', [
            'title' => 'Kelola Data Penerbit',
            'publishers' => $publishers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:publishers,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Publisher::create($validated);

        return redirect()->route('publishers.index')->with('success', 'Penerbit berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:publishers,name,' . $publisher->id],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $publisher->update($validated);

        return redirect()->route('publishers.index')->with('success', 'Penerbit berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publisher $publisher)
    {
        $publisher->delete();

        return redirect()->route('publishers.index')->with('success', 'Penerbit berhasil dihapus.');
    }
}
