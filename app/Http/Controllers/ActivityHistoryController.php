<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Fine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityHistoryController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $borrowings = Borrowing::with(['user', 'book'])
            ->when($dateFrom, function($query) use ($dateFrom) {
                return $query->whereDate('borrow_date', '>=', $dateFrom);
            })
            ->when($dateTo, function($query) use ($dateTo) {
                return $query->whereDate('borrow_date', '<=', $dateTo);
            })
            ->orderBy('borrow_date', 'desc')
            ->get();
        
        $fines = Fine::with('borrowing.user', 'borrowing.book')
            ->when($dateFrom, function($query) use ($dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function($query) use ($dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        $stats = [
            'total_borrowed' => Borrowing::where('status', 'borrowed')->count(),
            'total_returned' => Borrowing::where('status', 'returned')->count(),
            'total_fines' => Fine::where('status', 'unpaid')->sum('amount'),
            'total_fines_collected' => Fine::where('status', 'paid')->sum('amount'),
        ];
        
        return view('pages.activity-history.index', [
            'title' => 'Riwayat Aktivitas',
            'borrowings' => $borrowings,
            'fines' => $fines,
            'stats' => $stats,
            'type' => $type,
        ]);
    }
    
    public function borrowed(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $borrowings = Borrowing::with(['user', 'book'])
            ->where('status', 'borrowed')
            ->when($dateFrom, function($query) use ($dateFrom) {
                return $query->whereDate('borrow_date', '>=', $dateFrom);
            })
            ->when($dateTo, function($query) use ($dateTo) {
                return $query->whereDate('borrow_date', '<=', $dateTo);
            })
            ->orderBy('borrow_date', 'desc')
            ->get();
        
        return view('pages.activity-history.borrowed', [
            'title' => 'Riwayat Peminjaman',
            'borrowings' => $borrowings,
        ]);
    }
    
    public function returned(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $borrowings = Borrowing::with(['user', 'book'])
            ->where('status', 'returned')
            ->when($dateFrom, function($query) use ($dateFrom) {
                return $query->whereDate('returned_at', '>=', $dateFrom);
            })
            ->when($dateTo, function($query) use ($dateTo) {
                return $query->whereDate('returned_at', '<=', $dateTo);
            })
            ->orderBy('returned_at', 'desc')
            ->get();
        
        return view('pages.activity-history.returned', [
            'title' => 'Riwayat Pengembalian',
            'borrowings' => $borrowings,
        ]);
    }
    
    public function fines(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $status = $request->get('status', 'all');
        
        $fines = Fine::with('borrowing.user', 'borrowing.book')
            ->when($dateFrom, function($query) use ($dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function($query) use ($dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->when($status !== 'all', function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('pages.activity-history.fines', [
            'title' => 'Riwayat Denda',
            'fines' => $fines,
        ]);
    }
}