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

    public function showBorrowForm()
    {
        return view('pages.member.create');
    }

    public function refreshTable()
    {
        $borrowings = Borrowing::with(['user', 'book'])->get();
        
        return view('pages.borrowing-returns.index', [
            'title' => 'Kelola Peminjaman & Pengembalian',
            'borrowings' => $borrowings,
            'users' => \App\Models\User::all(),
            'books' => \App\Models\Book::all(),
            'roles' => \App\Models\Role::all(),
            'totalBorrowed' => Borrowing::where('status', 'borrowed')->count(),
            'totalBorrowRequests' => Borrowing::where('status', 'requested')->count(),
            'totalReturnRequests' => Borrowing::where('status', 'return_requested')->count(),
        ])->render();
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
        $validated = $request->validate([
            'days' => ['required', 'integer', 'min:1', 'max:14'],
        ]);

        $days = $validated['days'];

        if ($book->stock < 1) {
            return back()->with('error', 'Buku sedang habis stok.');
        }

        if (Borrowing::where('book_id', $book->id)
            ->where('user_id', Auth::id())
            ->whereNull('returned_at')
            ->exists()
        ) {
            return back()->with('error', 'Anda sudah mengajukan atau meminjam buku ini dan belum menyelesaikannya.');
        }

        $dueDate = now()->addDays((int)$days)->toDateString();

        Borrowing::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'role_id' => Auth::user()->role_id,
            'borrow_date' => now()->toDateString(),
            'due_date' => $dueDate,
            'status' => 'requested',
            'notes' => 'Pengajuan pinjaman dikirim ke petugas/admin. Durasi: ' . (int)$days . ' hari.',
        ]);

        return back()->with('success', 'Pengajuan peminjaman buku telah dikirim ke petugas/admin. Jatuh tempo: ' . \Carbon\Carbon::parse($dueDate)->format('d/m/Y'));
    }

    public function memberReturn(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id()) {
            abort(403);
        }

        if ($borrowing->status !== 'borrowed') {
            return back()->with('error', 'Hanya peminjaman dengan status dipinjam yang bisa diajukan pengembaliannya.');
        }

        // Check if member is overdue - block ALL returns if overdue
        $dueDate = \Carbon\Carbon::parse($borrowing->due_date);
        $isOverdue = now()->greaterThan($dueDate);
        
        // Calculate and save fine if overdue
        if ($isOverdue) {
            $daysLate = (int)now()->diffInDays($dueDate);
            $finePerDay = $borrowing->book->fine_per_day ?? 5000;
            $fine = $daysLate * $finePerDay;
            
            // Block return if there's any fine
            if ($fine > 0) {
                $borrowing->update([
                    'fine' => $fine,
                    'fine_status' => 'unpaid',
                ]);
                return back()->with('error', 'Buku terlambat! Anda memiliki denda Rp ' . number_format($fine, 0, ',', '.') . '. Silakan bayarkan dulu sebelum mengajukan pengembalian.');
            }
        }

        // Check for any unpaid fine (including negative or zero)
        if ($borrowing->fine_status === 'unpaid' && $borrowing->fine != 0) {
            return back()->with('error', 'Anda memiliki denda yang belum dibayar. Silakan bayarkan dulu sebelum mengajukan pengembalian.');
        }

        $borrowing->update([
            'status' => 'return_requested',
            'notes' => 'Pengajuan pengembalian dikirim ke petugas/admin.',
        ]);

        return back()->with('success', 'Pengajuan pengembalian buku telah dikirim ke petugas/admin.');
    }

    public function create()
    {
        $users = \App\Models\User::all();
        $books = \App\Models\Book::all();
        $roles = \App\Models\Role::all();

        return view('pages.borrowing-returns.create.index', [
            'title' => 'Tambah Peminjaman',
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
            'status' => ['required', 'in:borrowed,returned,overdue,requested,return_requested'],
        ]);

        Borrowing::create($validated);

        return redirect()->route('borrowing-returns.index')->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    public function edit(Borrowing $borrowing)
    {
        $users = \App\Models\User::all();
        $books = \App\Models\Book::all();
        $roles = \App\Models\Role::all();

        return view('pages.borrowing-returns.edit.index', [
            'title' => 'Edit Peminjaman',
            'borrowing' => $borrowing,
            'users' => $users,
            'books' => $books,
            'roles' => $roles,
        ]);
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

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Peminjaman berhasil dihapus.']);
        }

        return redirect()->route('borrowing-returns.index')->with('success', 'Peminjaman berhasil dihapus.');
    }

    public function returnBook(Request $request, Borrowing $borrowing)
    {
        if (!in_array($borrowing->status, ['borrowed', 'return_requested'], true)) {
            return redirect()->route('borrowing-returns.index')->with('error', 'Hanya peminjaman aktif yang dapat dikembalikan.');
        }

        $borrowedBook = $borrowing->book;
        if ($borrowedBook) {
            $borrowedBook->increment('stock');
        }

        $returnDate = now();
        $dueDate = \Carbon\Carbon::parse($borrowing->due_date);
        
        $fine = 0;
        if ($returnDate->greaterThan($dueDate)) {
            $daysLate = (int)$returnDate->diffInDays($dueDate);
            $finePerDay = $borrowedBook->fine_per_day ?? 5000;
            $fine = $daysLate * $finePerDay;
        }

        $borrowing->update([
            'returned_at' => $returnDate,
            'status' => 'returned',
            'fine' => $fine,
            'fine_status' => $fine > 0 ? 'unpaid' : 'paid',
            'paid_at' => $fine > 0 ? null : now(),
            'notes' => 'Buku dikembalikan. ' . ($fine > 0 ? 'Denda: Rp ' . number_format($fine, 0, ',', '.') : ''),
        ]);

        return redirect()->route('borrowing-returns.index')->with('success', 'Buku berhasil dikembalikan.' . ($fine > 0 ? ' Denda: Rp ' . number_format($fine, 0, ',', '.') : ''));
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

        if ($borrowing->fine > 0 && $borrowing->fine_status === 'unpaid') {
            return redirect()->route('borrowing-returns.index')->with('error', 'Anggota belum membayar denda. Tunggu anggota membayar terlebih dahulu.');
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

    public function rejectReturn(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'return_requested') {
            return response()->json(['success' => false, 'message' => 'Hanya pengajuan pengembalian yang dapat ditolak.']);
        }

        $borrowing->update([
            'status' => 'borrowed',
            'notes' => 'Pengajuan pengembalian ditolak oleh petugas/admin.',
        ]);

        return response()->json(['success' => true, 'message' => 'Pengembalian ditolak.']);
    }

    public function payFine(Borrowing $borrowing)
    {
        // Calculate fine if overdue but not saved
        $dueDate = \Carbon\Carbon::parse($borrowing->due_date);
        $isOverdue = now()->greaterThan($dueDate);
        
        $fine = $borrowing->fine;
        if ($isOverdue && $fine <= 0) {
            $daysLate = (int)now()->diffInDays($dueDate);
            $finePerDay = optional($borrowing->book)->fine_per_day ?? 5000;
            $fine = $daysLate * $finePerDay;
        }

        // Allow payment if: fine > 0, OR status is 'unpaid', OR is overdue with calculated fine
        $canPay = $fine > 0 || $borrowing->fine_status === 'unpaid' || $isOverdue;
        
        if (!$canPay) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada denda yang perlu dibayar.']);
            }
            return back()->with('error', 'Tidak ada denda yang perlu dibayar.');
        }

        $borrowing->update([
            'fine' => $fine,
            'fine_status' => 'paid',
            'paid_at' => now(),
        ]);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Denda berhasil dibayar.']);
        }
        return back()->with('success', 'Denda berhasil dibayar.');
    }
}
