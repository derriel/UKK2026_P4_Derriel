<?php

// Import controller yang digunakan dalam routing
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportsController;
use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;

// Grup route yang memerlukan autentikasi (middleware auth)
Route::middleware('auth')->group(function () {
    // Route untuk halaman dashboard utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk halaman manajemen buku
    Route::resource('books', BookController::class)
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

    // Route untuk halaman laporan
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
});

// Route untuk halaman root (/)
Route::get('/', function () {
    // Jika user sudah login, redirect ke dashboard
    if (auth()->check()) {
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






















