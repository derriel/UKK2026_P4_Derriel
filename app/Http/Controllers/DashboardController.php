<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMembers = Schema::hasTable('users') ? User::count() : 0;
        $totalBooks = Schema::hasTable('books') ? DB::table('books')->count() : 0;
        $totalBorrowed = 0;

        $topBorrowedBooks = collect([
            ['title' => 'Pemrograman Dasar', 'borrow_count' => 16],
            ['title' => 'Basis Data', 'borrow_count' => 12],
            ['title' => 'Algoritma & Struktur Data', 'borrow_count' => 9],
            ['title' => 'Sistem Informasi', 'borrow_count' => 7],
            ['title' => 'Manajemen Proyek', 'borrow_count' => 5],
        ]);

        if (Schema::hasTable('borrowings')) {
            $totalBorrowed = DB::table('borrowings')
                ->whereNull('returned_at')
                ->count();

            $topBorrowedBooks = DB::table('borrowings')
                ->join('books', 'borrowings.book_id', '=', 'books.id')
                ->select('books.title', DB::raw('count(borrowings.id) as borrow_count'))
                ->groupBy('books.id', 'books.title')
                ->orderByDesc('borrow_count')
                ->limit(5)
                ->get();
        }

        $activeUsers = [];
        $offlineUsers = [];
        $activeAccounts = 0;

        if (Schema::hasTable('sessions') && Schema::hasTable('users')) {
            $sessionLifetime = config('session.lifetime', 120);
            $threshold = Carbon::now()->subMinutes($sessionLifetime)->timestamp;

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

            $onlineIds = array_unique($onlineIds);
            $activeAccounts = count($onlineIds);

            $users = User::orderBy('name')->get(['id', 'name', 'email']);
            foreach ($users as $user) {
                $status = in_array($user->id, $onlineIds) ? 'Online' : 'Offline';
                $target = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $status,
                ];

                if ($status === 'Online') {
                    $activeUsers[] = $target;
                } else {
                    $offlineUsers[] = $target;
                }
            }
        } elseif (Schema::hasTable('users')) {
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

        $userStatuses = array_merge($activeUsers, $offlineUsers);
        $userStatuses = array_slice($userStatuses, 0, 10);

        return view('pages.dashboard.ecommerce', compact(
            'totalBooks',
            'totalBorrowed',
            'totalMembers',
            'activeAccounts',
            'topBorrowedBooks',
            'userStatuses'
        ));
    }

    private function extractUserIdFromPayload(string $payload): ?int
    {
        $decoded = @base64_decode($payload, true);
        $data = null;

        if ($decoded !== false && $decoded !== $payload) {
            $data = @unserialize($decoded);
        }

        if ($data === false) {
            $data = @unserialize($payload);
        }

        if (!is_array($data)) {
            return null;
        }

        foreach ($data as $key => $value) {
            if (preg_match('/^login_/', $key) && is_array($value) && isset($value['id'])) {
                return (int) $value['id'];
            }
        }

        return null;
    }
}
