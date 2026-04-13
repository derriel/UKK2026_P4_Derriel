<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import controller yang digunakan dalam routing
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PublisherController;
use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;

// Grup route yang memerlukan autentikasi (middleware auth)
Route::middleware('auth')->group(function () {
    // Route untuk halaman dashboard admin
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    // Route untuk halaman dashboard petugas (librarian)
    Route::get('/dashboard-petugas', [DashboardController::class, 'petugasDashboard'])->name('dashboard_petugas');
    // Route untuk halaman member welcome
    Route::view('/welcome', 'pages.welcome', ['title' => 'Welcome'])->name('welcome');

    // Route untuk halaman manajemen buku
    Route::resource('books', BookController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Route untuk halaman manajemen pengarang
    Route::resource('authors', AuthorController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Route untuk halaman manajemen penerbit
    Route::resource('publishers', PublisherController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Route untuk halaman manajemen anggota
    Route::resource('members', MemberController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Route untuk halaman manajemen users
    Route::resource('users', UserController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Route untuk halaman manajemen peminjaman dan pengembalian
    Route::resource('borrowing-returns', BorrowingController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    Route::post('borrowing-returns/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('borrowing-returns.return');

    // Member-specific pages
    Route::get('/member/books', [BookController::class, 'catalog'])->name('member.books.index');
    Route::get('/member/borrowings', [BorrowingController::class, 'memberIndex'])->name('member.borrowings.index');
    Route::get('/member/profile', [MemberController::class, 'profile'])->name('member.profile');

    // Route untuk halaman laporan
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');

    // Route untuk halaman App Config dan Appeal
    Route::view('/app-config', 'pages.app-config', ['title' => 'App Config'])->name('app-config');
    Route::view('/appeal-monitor', 'pages.appeals.monitor', ['title' => 'Memantau Appeal'])->name('appeal-monitor');
    Route::view('/appeal-submit', 'pages.appeals.submit', ['title' => 'Mengajukan Appeal'])->name('appeal-submit');
    Route::view('/testing', 'pages.testing.index', ['title' => 'Testing (Test Matriks)'])->name('testing');
});

// Route untuk halaman root (/)
Route::get('/', function () {
    // Jika user sudah login, redirect sesuai role
    if (Auth::check()) {
        $roleName = optional(Auth::user()->role)->name;
        if (strtolower($roleName) === 'member') {
            return redirect()->route('welcome');
        }
        return redirect()->route('dashboard');
    }
    // Jika belum login, tampilkan halaman login
    return view('pages.auth.signin', ['title' => 'Login']);
})->name('login');

// Route untuk menangani POST request login
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
// Route untuk menangani POST request registrasi
Route::post('/signup', [App\Http\Controllers\AuthController::class, 'register'])->name('signup.post');
// Route untuk menangani POST request logout
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Route untuk halaman autentikasi (tidak memerlukan middleware auth)
// Route untuk halaman login
Route::get('/login', function () {
    if (Auth::check()) {
        $roleName = optional(Auth::user()->role)->name;
        if (strtolower($roleName) === 'member') {
            return redirect()->route('welcome');
        }
        return redirect()->route('dashboard');
    }
    return view('pages.auth.signin', ['title' => 'Login']);
})->name('login');

// Route untuk halaman registrasi
Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Register']);
})->name('signup');

// Route redirect dari /register ke /signup
Route::get('/register', function () {
    return redirect()->route('signup');
});

// Route untuk fitur reset password
// Route untuk menampilkan form lupa password
Route::get('/forgot-password', [App\Http\Controllers\AuthController::class, 'showForgotPasswordForm'])->name('password.request');
// Route untuk mengirim link reset password via email
Route::post('/forgot-password', [App\Http\Controllers\AuthController::class, 'sendPasswordResetLink'])->name('password.email');
// Route untuk menampilkan form reset password
Route::get('/reset-password', [App\Http\Controllers\AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
// Route untuk menangani reset password
Route::post('/reset-password', [App\Http\Controllers\AuthController::class, 'resetPassword'])->name('password.reset');






















