<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['user', 'book'])->get();
        $users = \App\Models\User::all();
        $books = \App\Models\Book::all();
        $roles = \App\Models\Role::all();

        return view('pages.borrowing-returns.index', [
            'title' => 'Kelola Peminjaman & Pengembalian',
            'borrowings' => $borrowings,
            'users' => $users,
            'books' => $books,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'book_id' => ['required', 'exists:books,id'],
            'role_id' => ['required', 'exists:roles,id'],
            'borrow_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after:borrow_date'],
            'return_date' => ['nullable', 'date', 'after_or_equal:borrow_date'],
            'status' => ['required', 'in:borrowed,returned,overdue'],
        ]);

        Borrowing::create($validated);

        return redirect()->route('borrowing-returns.index')->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'book_id' => ['required', 'exists:books,id'],
            'role_id' => ['required', 'exists:roles,id'],
            'borrow_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after:borrow_date'],
            'return_date' => ['nullable', 'date', 'after_or_equal:borrow_date'],
            'status' => ['required', 'in:borrowed,returned,overdue'],
        ]);

        $borrowing->update($validated);

        return redirect()->route('borrowing-returns.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function destroy(Borrowing $borrowing)
    {
        $borrowing->delete();

        return redirect()->route('borrowing-returns.index')->with('success', 'Peminjaman berhasil dihapus.');
    }

    public function returnBook(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'returned_at' => ['required', 'date', 'after_or_equal:borrow_date'],
        ]);

        $borrowing->update([
            'returned_at' => $validated['returned_at'],
            'status' => 'returned',
        ]);

        return redirect()->route('borrowing-returns.index')->with('success', 'Buku berhasil dikembalikan.');
    }
}