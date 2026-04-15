<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import controller yang digunakan dalam routing
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\ProfileController;
use App\Models\Book;
use App\Models\Siswa;
use App\Models\Borrowing;

// Grup route yang memerlukan autentikasi (middleware auth)
Route::middleware('auth')->group(function () {
    // Route untuk halaman dashboard admin
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    // Route untuk halaman dashboard petugas (librarian)
    Route::get('/dashboard-petugas', [DashboardController::class, 'petugasDashboard'])->name('dashboard_petugas');
    // Route untuk halaman member welcome
    Route::get('/welcome', [App\Http\Controllers\MemberController::class, 'welcome'])->name('welcome');

    // Route untuk halaman manajemen buku
    Route::resource('books', BookController::class)
        ->only(['index', 'create', 'edit', 'store', 'update', 'destroy']);

    // Route untuk halaman manajemen pengarang
    Route::resource('authors', AuthorController::class)
        ->only(['index', 'create', 'edit', 'store', 'update', 'destroy']);

    // Route untuk halaman manajemen penerbit
    Route::resource('publishers', PublisherController::class)
        ->only(['index', 'create', 'edit', 'store', 'update', 'destroy']);

    // Route untuk halaman manajemen kategori buku
    Route::resource('categories', CategoryController::class)
        ->only(['index', 'create', 'edit', 'store', 'update', 'destroy']);

    // Route untuk halaman manajemen rak buku
    Route::resource('racks', RackController::class)
        ->only(['index', 'create', 'edit', 'store', 'update', 'destroy']);

    // Route untuk halaman manajemen anggota
    Route::resource('members', SiswaController::class)
        ->only(['index', 'create', 'edit', 'store', 'update', 'destroy']);

    // Route untuk halaman manajemen users
    Route::resource('users', UserController::class)
        ->only(['index', 'create', 'edit', 'store', 'update', 'destroy']);
    Route::get('users-status', [UserController::class, 'status'])->name('users.status');

    // Route untuk halaman manajemen peminjaman dan pengembalian
    Route::resource('borrowing-returns', BorrowingController::class)
        ->only(['index', 'create', 'edit', 'store', 'update', 'destroy']);
    Route::post('borrowing-returns/{borrowing}/approve-borrow', [BorrowingController::class, 'approveBorrow'])->name('borrowing-returns.approveBorrow');
    Route::post('borrowing-returns/{borrowing}/approve-return', [BorrowingController::class, 'approveReturn'])->name('borrowing-returns.approveReturn');
    Route::post('borrowing-returns/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('borrowing-returns.return');
    Route::post('borrowing-returns/{borrowing}/pay-fine', [BorrowingController::class, 'payFine'])->name('borrowing-returns.payFine');

    // Member-specific pages
    Route::get('/member/books', [BookController::class, 'catalog'])->name('member.books.index');
    Route::get('/member/books/{book}', [BookController::class, 'show'])->name('member.books.show');
    Route::post('/member/books/{book}/borrow', [BorrowingController::class, 'borrow'])->name('member.books.borrow');
    Route::get('/member/borrowings', [BorrowingController::class, 'memberIndex'])->name('member.borrowings.index');
    Route::put('/member/borrowings/{borrowing}/return', [BorrowingController::class, 'memberReturn'])->name('member.borrowings.return');
    Route::post('/member/borrowings/{borrowing}/pay-fine', [BorrowingController::class, 'payFine'])->name('member.borrowings.payFine');
    Route::get('/member/profile', [ProfileController::class, 'index'])->name('member.profile');
    Route::put('/member/profile', [ProfileController::class, 'update'])->name('member.profile.update');

    // Route untuk halaman laporan
    // Berfungsi untuk admin dan petugas melihat rekap data perpustakaan
    // Fitur: Filter bulan/tahun, Export Excel, Cetak
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/filter', [ReportsController::class, 'filter'])->name('reports.filter');
    Route::get('/reports/export', [ReportsController::class, 'export'])->name('reports.export');
    Route::get('/reports/print', [ReportsController::class, 'print'])->name('reports.print');

    // Route untuk halaman App Config
    Route::view('/app-config', 'pages.app-config', ['title' => 'App Config'])->name('app-config');
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

Route::get('/user-status', [UserController::class, 'status'])->name('user.status');
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/chat', function () {
    return view('chat');
})->name('chat');





















