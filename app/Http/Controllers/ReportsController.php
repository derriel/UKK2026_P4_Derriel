<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Controller untuk halaman Laporan
 * Berfungsi untuk admin dan petugas melihat rekap data perpustakaan
 * - Rekap peminjaman bulanan
 * - Stok buku
 * - Anggota paling aktif
 */
class ReportsController extends Controller
{
    public function index()
    {
        return $this->generateReport(
            Carbon::now()->month,
            Carbon::now()->year
        );
    }

    /**
     * Filter laporan berdasarkan bulan dan tahun
     */
    public function filter(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        return $this->generateReport($month, $year);
    }

    /**
     * Export laporan ke Excel (Format HTML Table)
     */
    public function export(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $borrowings = Borrowing::with(['user', 'book'])
            ->whereYear('borrow_date', $year)
            ->whereMonth('borrow_date', $month)
            ->get();

        $books = Book::all();
        $members = User::where('role_id', 3)->withCount(['borrowings'])->orderByDesc('borrowings_count')->take(10)->get();

        $monthName = $this->getMonthName($month);
        
        // Generate HTML content for Excel
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #FFE699; font-weight: bold; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .subtitle { font-size: 14px; margin-bottom: 15px; }
        .normal { background-color: #C6EFCE; }
        .rendah { background-color: #FFEB9C; }
        .kritis { background-color: #FFC7CE; }
    </style>
</head>
<body>
    <div class="title">LAPORAN PERPUSTAKAAN</div>
    <div class="subtitle">Bulan: ' . $monthName . ' ' . $year . '</div>
    
    <h3>Laporan Peminjaman Bulanan</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Anggota</th>
            <th>Judul Buku</th>
            <th>Tanggal Peminjaman</th>
            <th>Tanggal Kembali</th>
            <th>Durasi (hari)</th>
        </tr>';
        
        foreach ($borrowings as $index => $borrowing) {
            $tglKembali = $borrowing->returned_at ? $borrowing->returned_at->format('Y-m-d') : '-';
            $durasi = $borrowing->returned_at && $borrowing->borrow_date ? $borrowing->borrow_date->diffInDays($borrowing->returned_at) : '-';
            
            $html .= '<tr>
                <td>' . ($index + 1) . '</td>
                <td>' . ($borrowing->user->name ?? 'Unknown') . '</td>
                <td>' . ($borrowing->book->title ?? 'Unknown') . '</td>
                <td>' . ($borrowing->borrow_date ? $borrowing->borrow_date->format('Y-m-d') : '-') . '</td>
                <td>' . $tglKembali . '</td>
                <td>' . $durasi . '</td>
            </tr>';
        }
        
        $html .= '</table>
    
    <h3>Laporan Stok Buku</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Judul Buku</th>
            <th>Pengarang</th>
            <th>Stok Awal</th>
            <th>Terpinjam</th>
            <th>Stok Akhir</th>
            <th>Status</th>
        </tr>';
        
        foreach ($books as $index => $book) {
            $borrowed = Borrowing::where('book_id', $book->id)->whereNull('returned_at')->count();
            $stokAkhir = $book->stock - $borrowed;
            $status = $stokAkhir > 5 ? 'Normal' : ($stokAkhir > 1 ? 'Rendah' : 'Kritis');
            $statusClass = $status == 'Normal' ? 'normal' : ($status == 'Rendah' ? 'rendah' : 'kritis');
            
            $html .= '<tr>
                <td>' . ($index + 1) . '</td>
                <td>' . $book->title . '</td>
                <td>' . (optional($book->author)->name ?? '-') . '</td>
                <td>' . $book->stock . '</td>
                <td>' . $borrowed . '</td>
                <td>' . $stokAkhir . '</td>
                <td class="' . $statusClass . '">' . $status . '</td>
            </tr>';
        }
        
        $html .= '</table>
    
    <h3>Daftar Anggota Paling Aktif</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Anggota</th>
            <th>Email</th>
            <th>Jumlah Peminjaman</th>
            <th>Sedang Dipinjam</th>
        </tr>';
        
        foreach ($members as $index => $member) {
            $currentBorrowed = Borrowing::where('user_id', $member->id)->whereNull('returned_at')->count();
            
            $html .= '<tr>
                <td>' . ($index + 1) . '</td>
                <td>' . $member->name . '</td>
                <td>' . $member->email . '</td>
                <td>' . ($member->borrowings_count ?? 0) . '</td>
                <td>' . $currentBorrowed . '</td>
            </tr>';
        }
        
        $html .= '</table>
</body>
</html>';

        $filename = 'laporan_perpustakaan_' . $month . '_' . $year . '.xls';
        
        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Cetak laporan (buka dialog print browser)
     */
    public function print(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        return $this->generateReport($month, $year);
    }

    /**
     * Helper: Get month name in Indonesian
     */
    private function getMonthName($month)
    {
        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        return $months[str_pad($month, 2, '0', STR_PAD_LEFT)] ?? $month;
    }

    /**
     * Helper: Generate laporan dengan parameter bulan dan tahun
     */
    private function generateReport($month, $year)
    {
        $totalBooks = Book::count();
        $totalMembers = User::where('role_id', 3)->count();
        $activeBorrowings = Borrowing::whereNull('returned_at')->count();
        $availableBooks = $totalBooks - $activeBorrowings;

        $borrowings = Borrowing::with(['user', 'book'])
            ->whereYear('borrow_date', $year)
            ->whereMonth('borrow_date', $month)
            ->get()
            ->map(function ($borrowing) {
                return (object) [
                    'id' => $borrowing->id,
                    'anggota' => $borrowing->user->name ?? 'Unknown',
                    'buku' => $borrowing->book->title ?? 'Unknown',
                    'tgl_pinjam' => $borrowing->borrow_date ? $borrowing->borrow_date->format('Y-m-d') : '-',
                    'tgl_kembali' => $borrowing->returned_at ? $borrowing->returned_at->format('Y-m-d') : '-',
                    'durasi' => $borrowing->returned_at && $borrowing->borrow_date ? $borrowing->borrow_date->diffInDays($borrowing->returned_at) : '-',
                ];
            });

        $books = Book::all()->map(function ($book) {
            $borrowed = Borrowing::where('book_id', $book->id)->whereNull('returned_at')->count();
            $stokAkhir = $book->stock - $borrowed;
            $status = $stokAkhir > 5 ? 'Normal' : ($stokAkhir > 1 ? 'Rendah' : 'Kritis');
            return (object) [
                'id' => $book->id,
                'judul' => $book->title,
                'pengarang' => optional($book->author)->name ?? $book->author,
                'stok_awal' => $book->stock,
                'terpinjam' => $borrowed,
                'stok_akhir' => $stokAkhir,
                'status' => $status,
            ];
        });

        $topMembers = User::where('role_id', 3)
            ->withCount(['borrowings'])
            ->orderByDesc('borrowings_count')
            ->take(10)
            ->get()
            ->map(function ($user) {
                $currentBorrowed = Borrowing::where('user_id', $user->id)->whereNull('returned_at')->count();
                return (object) [
                    'id' => $user->id,
                    'nama' => $user->name,
                    'identitas' => $user->email,
                    'total_pinjam' => $user->borrowings_count ?? 0,
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
            'currentMonth' => (int) $month,
            'currentYear' => (int) $year,
        ]);
    }
}
