<?php

// Namespace untuk controller
namespace App\Http\Controllers;

// Import model dan facade yang diperlukan
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

// Controller untuk menangani dashboard aplikasi
class DashboardController extends Controller
{
    // Fungsi untuk menampilkan dashboard admin (ecommerce)
    public function adminDashboard()
    {
        return $this->getDashboardData('pages.dashboard.ecommerce');
    }

    // Fungsi untuk menampilkan dashboard petugas (librarian)
    public function petugasDashboard()
    {
        return $this->getDashboardData('pages.dashboard.dashboard_petugas');
    }

    // Fungsi untuk menampilkan halaman dashboard dengan statistik
    public function getDashboardData($view)
    {
        // Hitung total anggota (users) jika tabel ada
        $totalMembers = Schema::hasTable('users') ? User::count() : 0;
        // Hitung total buku jika tabel ada
        $totalBooks = Schema::hasTable('books') ? DB::table('books')->count() : 0;
        // Inisialisasi total peminjaman yang belum dikembalikan
        $totalBorrowed = 0;

        // Data dummy untuk buku paling sering dipinjam (fallback)
        $topBorrowedBooks = collect([
            ['title' => 'Pemrograman Dasar', 'borrow_count' => 16],
            ['title' => 'Basis Data', 'borrow_count' => 12],
            ['title' => 'Algoritma & Struktur Data', 'borrow_count' => 9],
            ['title' => 'Sistem Informasi', 'borrow_count' => 7],
            ['title' => 'Manajemen Proyek', 'borrow_count' => 5],
        ]);

        // Jika tabel borrowings ada, hitung total peminjaman aktif dan ambil top borrowed books
        if (Schema::hasTable('borrowings')) {
            $totalBorrowed = DB::table('borrowings')
                ->whereNull('returned_at') // Yang belum dikembalikan
                ->count();

            // Query untuk mendapatkan buku paling sering dipinjam
            $topBorrowedBooks = DB::table('borrowings')
                ->join('books', 'borrowings.book_id', '=', 'books.id')
                ->select('books.title', DB::raw('count(borrowings.id) as borrow_count'))
                ->groupBy('books.id', 'books.title')
                ->orderByDesc('borrow_count')
                ->limit(5)
                ->get();
        }

        // Inisialisasi array untuk pengguna aktif dan offline
        $activeUsers = [];
        $offlineUsers = [];
        $activeAccounts = 0;

        // Jika tabel sessions dan users ada, cek pengguna online berdasarkan session
        if (Schema::hasTable('sessions') && Schema::hasTable('users')) {
            // Ambil lifetime session dari config
            $sessionLifetime = config('session.lifetime', 120);
            // Hitung threshold waktu untuk dianggap online
            $threshold = Carbon::now()->subMinutes($sessionLifetime)->timestamp;

            // Ambil session yang aktif
            $sessionRows = DB::table('sessions')
                ->where('last_activity', '>=', $threshold)
                ->get();

            // Ekstrak user ID dari payload session
            $onlineIds = [];
            foreach ($sessionRows as $session) {
                $userId = $this->extractUserIdFromPayload($session->payload);
                if ($userId) {
                    $onlineIds[] = (int) $userId;
                }
            }

            // Hilangkan duplikat dan hitung jumlah akun aktif
            $onlineIds = array_unique($onlineIds);
            $activeAccounts = count($onlineIds);

            // Ambil semua users dan tentukan status online/offline
            $users = User::orderBy('name')->get(['id', 'name', 'email']);
            foreach ($users as $user) {
                $status = in_array($user->id, $onlineIds) ? 'Online' : 'Offline';
                $target = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $status,
                ];

                // Pisahkan ke array aktif atau offline
                if ($status === 'Online') {
                    $activeUsers[] = $target;
                } else {
                    $offlineUsers[] = $target;
                }
            }
        } elseif (Schema::hasTable('users')) {
            // Jika hanya tabel users ada, semua dianggap offline
            $users = User::orderBy('name')->get(['id', 'name', 'email']);
            foreach ($users as $user) {
                $offlineUsers[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => 'Offline',
                ];
            }
        }

        // Gabungkan dan batasi ke 10 pengguna untuk ditampilkan
        $userStatuses = array_merge($activeUsers, $offlineUsers);
        $userStatuses = array_slice($userStatuses, 0, 10);

        // Return view dashboard dengan data yang dikumpulkan
        return view($view, compact(
            'totalBooks',
            'totalBorrowed',
            'totalMembers',
            'activeAccounts',
            'topBorrowedBooks',
            'userStatuses'
        ));
    }

    // Fungsi privat untuk mengekstrak user ID dari payload session Laravel
    private function extractUserIdFromPayload(string $payload): ?int
    {
        // Decode payload dari base64
        $decoded = @base64_decode($payload, true);
        $data = null;

        // Jika decode berhasil, unserialize
        if ($decoded !== false && $decoded !== $payload) {
            $data = @unserialize($decoded);
        }

        // Jika gagal, coba unserialize langsung
        if ($data === false) {
            $data = @unserialize($payload);
        }

        // Jika bukan array, return null
        if (!is_array($data)) {
            return null;
        }

        // Cari key yang mengandung login_ dan ambil ID
        foreach ($data as $key => $value) {
            if (preg_match('/^login_/', $key) && is_array($value) && isset($value['id'])) {
                return (int) $value['id'];
            }
        }

        return null;
    }
}
