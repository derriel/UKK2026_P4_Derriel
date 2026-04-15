<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function welcome()
    {
        $recentBooks = Book::with(['author', 'publisher', 'category'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $popularBooks = Book::with(['author', 'publisher', 'category'])
            ->withCount('borrowings')
            ->orderByDesc('borrowings_count')
            ->take(8)
            ->get();

        $activeBorrowings = Borrowing::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved', 'borrowed'])
            ->count();

        return view('pages.welcome', [
            'title' => 'Welcome',
            'recentBooks' => $recentBooks,
            'popularBooks' => $popularBooks,
            'activeBorrowings' => $activeBorrowings,
        ]);
    }
}
