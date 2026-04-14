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
        // Inisialisasi total peminjaman, pengajuan, dan pengembalian
        $totalBorrowed = 0;
        $totalBorrowRequests = 0;
        $totalReturnRequests = 0;

        // Data dummy untuk buku paling sering dipinjam (fallback)
        $topBorrowedBooks = collect([
            ['title' => 'Pemrograman Dasar', 'borrow_count' => 16],
            ['title' => 'Basis Data', 'borrow_count' => 12],
            ['title' => 'Algoritma & Struktur Data', 'borrow_count' => 9],
            ['title' => 'Sistem Informasi', 'borrow_count' => 7],
            ['title' => 'Manajemen Proyek', 'borrow_count' => 5],
        ]);

        // Jika tabel borrowings ada, hitung status peminjaman dan ambil top borrowed books
        if (Schema::hasTable('borrowings')) {
            $totalBorrowed = DB::table('borrowings')
                ->where('status', 'borrowed')
                ->count();

            $totalBorrowRequests = DB::table('borrowings')
                ->where('status', 'requested')
                ->count();

            $totalReturnRequests = DB::table('borrowings')
                ->where('status', 'return_requested')
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
        $activeMembers = [];
        $offlineMembers = [];
        $activeAccounts = 0;

        $onlineIds = [];
        if (Schema::hasTable('sessions')) {
            $onlineIds = $this->getOnlineUserIdsFromSessions(5);
        }

        if (Schema::hasTable('users')) {
            $users = User::with('role')->orderBy('name')->get(['id', 'name', 'email', 'role_id']);
            foreach ($users as $user) {
                $status = in_array($user->id, $onlineIds, true) ? 'Online' : 'Offline';
                $target = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $status,
                ];

                // Pisahkan berdasarkan role
                $roleName = strtolower($user->role?->name ?? '');
                if ($roleName === 'member') {
                    if ($status === 'Online') {
                        $activeMembers[] = $target;
                    } else {
                        $offlineMembers[] = $target;
                    }
                } else {
                    if ($status === 'Online') {
                        $activeUsers[] = $target;
                    } else {
                        $offlineUsers[] = $target;
                    }
                }
            }

            $activeAccounts = count($activeUsers) + count($activeMembers);
        }

        // Gabungkan status users dan members
        $userStatuses = array_merge($activeUsers, $offlineUsers);
        $userStatuses = array_slice($userStatuses, 0, 10);

        $memberStatuses = array_merge($activeMembers, $offlineMembers);
        $memberStatuses = array_slice($memberStatuses, 0, 10);

        // Build data array based on which view is being shown
        $data = compact(
            'totalBooks',
            'totalBorrowed',
            'totalBorrowRequests',
            'totalReturnRequests',
            'totalMembers',
            'activeAccounts',
            'topBorrowedBooks'
        );

        // Add view-specific data
        if ($view === 'pages.dashboard.ecommerce') {
            $data['userStatuses'] = $userStatuses;
        } else {
            $data['memberStatuses'] = $memberStatuses;
        }

        return view($view, $data);
    }

    // Fungsi privat untuk mendapatkan user ID yang aktif berdasarkan session
    private function getOnlineUserIdsFromSessions(int $minutes = 5): array
    {
        $threshold = Carbon::now()->subMinutes($minutes)->timestamp;
        $sessionRows = DB::table('sessions')
            ->where('last_activity', '>=', $threshold)
            ->get();

        $onlineIds = [];
        foreach ($sessionRows as $session) {
            $userId = $this->extractUserIdFromPayload($session->payload);
            if ($userId) {
                $onlineIds[] = (int) $userId;
            }
        }

        return array_unique($onlineIds);
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
            if (!preg_match('/^login_/', $key)) {
                continue;
            }

            if (is_int($value) || ctype_digit((string) $value)) {
                return (int) $value;
            }

            if (is_array($value) && isset($value['id'])) {
                return (int) $value['id'];
            }

            if (is_object($value) && property_exists($value, 'id')) {
                return (int) $value->id;
            }
        }

        return null;
    }
}
