<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Rack;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with(['author', 'publisher', 'category', 'rack'])->get();
        $authors = Author::all();
        $publishers = Publisher::all();
        $categories = Category::all();

        return view('pages.books.index', [
            'title' => 'Kelola Data Buku',
            'books' => $books,
            'authors' => $authors,
            'publishers' => $publishers,
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $authors = Author::all();
        $publishers = Publisher::all();
        $categories = Category::all();
        $racks = Rack::all();

        return view('pages.books.create.index', [
            'title' => 'Tambah Buku',
            'authors' => $authors,
            'publishers' => $publishers,
            'categories' => $categories,
            'racks' => $racks,
        ]);
    }

    public function edit(Book $book)
    {
        $authors = Author::all();
        $publishers = Publisher::all();
        $categories = Category::all();
        $racks = Rack::all();

        return view('pages.books.edit.index', [
            'title' => 'Edit Buku',
            'book' => $book,
            'authors' => $authors,
            'publishers' => $publishers,
            'categories' => $categories,
            'racks' => $racks,
        ]);
    }

    public function catalog()
    {
        $search = request('search');
        
        $books = Book::with(['author', 'publisher', 'category']);
        
        if ($search) {
            $books->where(function($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas('author', function($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('publisher', function($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('isbn', 'like', '%' . $search . '%');
            });
        }
        
        $books = $books->get();

        return view('pages.member.books', [
            'title' => 'Katalog Buku',
            'books' => $books,
            'search' => $search,
        ]);
    }

    public function show(Book $book)
    {
        $book->load(['author', 'publisher', 'category', 'rack']);

        return view('pages.member.book-detail', [
            'title' => 'Detail Buku',
            'book' => $book,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author_id' => ['required', 'exists:authors,id'],
            'publisher_id' => ['nullable', 'exists:publishers,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'isbn' => ['nullable', 'string', 'max:255'],
            'publication_year' => ['nullable', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'description' => ['nullable', 'string', 'max:1000'],
            'stock' => ['required', 'integer', 'min:0'],
            'fine_per_day' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('books', 'public');
        }

        if (!isset($validated['fine_per_day'])) {
            $validated['fine_per_day'] = 5000;
        }
        
        $validated['is_active'] = $request->has('is_active') ? true : true;

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author_id' => ['required', 'exists:authors,id'],
            'publisher_id' => ['nullable', 'exists:publishers,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'isbn' => ['nullable', 'string', 'max:255'],
            'publication_year' => ['nullable', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'description' => ['nullable', 'string', 'max:1000'],
            'stock' => ['required', 'integer', 'min:0'],
            'fine_per_day' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('books', 'public');
        }

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $books = Book::with(['author', 'publisher', 'category'])
            ->where('title', 'like', '%' . $query . '%')
            ->orWhereHas('author', function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })
            ->orWhereHas('publisher', function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })
            ->get();

        return view('pages.member.search-results', [
            'title' => 'Hasil Pencarian: ' . $query,
            'books' => $books,
        ]);
    }
}
