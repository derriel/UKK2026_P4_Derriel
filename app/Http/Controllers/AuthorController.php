<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
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
        $authors = Author::all();

        return view('pages.authors.index', [
            'title' => 'Kelola Data Pengarang',
            'authors' => $authors,
        ]);
    }

    public function create()
    {
        return view('pages.authors.create.index', [
            'title' => 'Tambah Pengarang',
        ]);
    }

    public function edit(Author $author)
    {
        return view('pages.authors.edit.index', [
            'title' => 'Edit Pengarang',
            'author' => $author,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:authors,name'],
            'email' => ['nullable', 'email', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
        ]);

        Author::create($validated);

        return redirect()->route('authors.index')->with('success', 'Pengarang berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:authors,name,' . $author->id],
            'email' => ['nullable', 'email', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
        ]);

        $author->update($validated);

        return redirect()->route('authors.index')->with('success', 'Pengarang berhasil diperbarui.');
    }

    public function destroy(Author $author)
    {
        $author->delete();

        return redirect()->route('authors.index')->with('success', 'Pengarang berhasil dihapus.');
    }
}
