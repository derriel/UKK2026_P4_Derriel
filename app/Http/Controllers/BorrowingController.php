<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['user', 'book'])->get();
        $users = \App\Models\User::all();
        $books = \App\Models\Book::all();
        $roles = \App\Models\Role::all();

        $totalBorrowed = Borrowing::where('status', 'borrowed')->count();
        $totalBorrowRequests = Borrowing::where('status', 'requested')->count();
        $totalReturnRequests = Borrowing::where('status', 'return_requested')->count();

        return view('pages.borrowing-returns.index', [
            'title' => 'Kelola Peminjaman & Pengembalian',
            'borrowings' => $borrowings,
            'users' => $users,
            'books' => $books,
            'roles' => $roles,
            'totalBorrowed' => $totalBorrowed,
            'totalBorrowRequests' => $totalBorrowRequests,
            'totalReturnRequests' => $totalReturnRequests,
        ]);
    }

    public function memberIndex()
    {
        $borrowings = Borrowing::with('book')
            ->where('user_id', Auth::id())
            ->get();

        return view('pages.member.borrowings', [
            'title' => 'Status Peminjaman',
            'borrowings' => $borrowings,
        ]);
    }

    public function borrow(Request $request, Book $book)
    {
        if ($book->stock < 1) {
            return back()->with('error', 'Buku sedang habis stok.');
        }

        if (Borrowing::where('book_id', $book->id)
            ->where('user_id', Auth::id())
            ->whereNull('returned_at')
            ->exists()) {
            return back()->with('error', 'Anda sudah mengajukan atau meminjam buku ini dan belum menyelesaikannya.');
        }

        Borrowing::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'role_id' => Auth::user()->role_id,
            'borrow_date' => now()->toDateString(),
            'due_date' => now()->addDays(7)->toDateString(),
            'status' => 'requested',
            'notes' => 'Pengajuan pinjaman dikirim ke petugas/admin.',
        ]);

        return back()->with('success', 'Pengajuan peminjaman buku telah dikirim ke petugas/admin.');
    }

    public function memberReturn(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id()) {
            abort(403);
        }

        if ($borrowing->status !== 'borrowed') {
            return back()->with('error', 'Hanya peminjaman dengan status dipinjam yang bisa diajukan pengembaliannya.');
        }

        $borrowing->update([
            'status' => 'return_requested',
            'notes' => 'Pengajuan pengembalian dikirim ke petugas/admin.',
        ]);

        return back()->with('success', 'Pengajuan pengembalian buku telah dikirim ke petugas/admin.');
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
            'status' => ['required', 'in:borrowed,returned,overdue,requested,return_requested'],
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
            'status' => ['required', 'in:borrowed,returned,overdue,requested,return_requested'],
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

        if (!in_array($borrowing->status, ['borrowed', 'return_requested'], true)) {
            return redirect()->route('borrowing-returns.index')->with('error', 'Hanya peminjaman aktif yang dapat dikembalikan.');
        }

        $borrowedBook = $borrowing->book;
        if ($borrowedBook) {
            $borrowedBook->increment('stock');
        }

        $borrowing->update([
            'returned_at' => $validated['returned_at'],
            'status' => 'returned',
        ]);

        return redirect()->route('borrowing-returns.index')->with('success', 'Buku berhasil dikembalikan.');
    }

    public function approveBorrow(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'requested') {
            return redirect()->route('borrowing-returns.index')->with('error', 'Hanya pengajuan peminjaman yang dapat disetujui.');
        }

        $book = $borrowing->book;
        if (!$book || $book->stock < 1) {
            return redirect()->route('borrowing-returns.index')->with('error', 'Stok buku tidak mencukupi untuk menyetujui peminjaman.');
        }

        $book->decrement('stock');

        $borrowing->update([
            'status' => 'borrowed',
            'notes' => 'Pengajuan peminjaman disetujui oleh petugas/admin.',
        ]);

        return redirect()->route('borrowing-returns.index')->with('success', 'Pengajuan peminjaman berhasil disetujui.');
    }

    public function approveReturn(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'return_requested') {
            return redirect()->route('borrowing-returns.index')->with('error', 'Hanya pengajuan pengembalian yang dapat disetujui.');
        }

        $book = $borrowing->book;
        if ($book) {
            $book->increment('stock');
        }

        $borrowing->update([
            'status' => 'returned',
            'returned_at' => now(),
            'notes' => 'Pengajuan pengembalian disetujui oleh petugas/admin.',
        ]);

        return redirect()->route('borrowing-returns.index')->with('success', 'Pengembalian berhasil disetujui.');
    }
}