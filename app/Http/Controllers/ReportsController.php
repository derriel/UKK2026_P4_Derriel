<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        // Summary data
        $totalBooks = Book::count();
        $totalMembers = User::where('role_id', 3)->count(); // Assuming role_id 3 is member
        $activeBorrowings = Borrowing::whereNull('returned_at')->count();
        $availableBooks = $totalBooks - $activeBorrowings;

        // Monthly borrowing report (default to current month)
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $borrowings = Borrowing::with(['user', 'book'])
            ->whereYear('borrow_date', $currentYear)
            ->whereMonth('borrow_date', $currentMonth)
            ->get()
            ->map(function ($borrowing) {
                return (object) [
                    'id' => $borrowing->id,
                    'anggota' => $borrowing->user->name ?? 'Unknown',
                    'buku' => $borrowing->book->title ?? 'Unknown',
                    'tgl_pinjam' => $borrowing->borrow_date->format('Y-m-d'),
                    'tgl_kembali' => $borrowing->returned_at ? $borrowing->returned_at->format('Y-m-d') : '-',
                    'durasi' => $borrowing->returned_at ? $borrowing->borrow_date->diffInDays($borrowing->returned_at) : '-',
                ];
            });

        // Book stock report
        $books = Book::all()->map(function ($book) {
            $borrowed = Borrowing::where('book_id', $book->id)->whereNull('returned_at')->count();
            $stokAkhir = $book->stock - $borrowed;
            $status = $stokAkhir > 5 ? 'Normal' : ($stokAkhir > 1 ? 'Rendah' : 'Kritis');
            return (object) [
                'id' => $book->id,
                'judul' => $book->title,
                'pengarang' => $book->author,
                'stok_awal' => $book->stock,
                'terpinjam' => $borrowed,
                'stok_akhir' => $stokAkhir,
                'status' => $status,
            ];
        });

        // Top members by borrowing count
        $topMembers = User::where('role_id', 3)
            ->withCount(['borrowings'])
            ->orderBy('borrowings_count', 'desc')
            ->take(10)
            ->get()
            ->map(function ($user) {
                $currentBorrowed = Borrowing::where('user_id', $user->id)->whereNull('returned_at')->count();
                return (object) [
                    'id' => $user->id,
                    'nama' => $user->name,
                    'identitas' => $user->email, // Using email as identity since no identity_number in User
                    'total_pinjam' => $user->borrowings_count,
                    'dipinjam_sekarang' => $currentBorrowed,
                ];
            });

        return view('pages.reports.index', [
            'title' => 'Laporan',
            'totalBooks' => $totalBooks,
            'totalMembers' => $totalMembers,
            'activeBorrowings' => $activeBorrowings,
            'availableBooks' => $availableBooks,
            'borrowings' => $borrowings,
            'books' => $books,
            'topMembers' => $topMembers,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
        ]);
    }
}