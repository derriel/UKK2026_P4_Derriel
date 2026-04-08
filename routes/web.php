<?php

use App\Http\Controllers\DashboardController;

// dashboard pages
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // books management pages
    Route::get('/books', function () {
        return view('pages.books.manage', ['title' => 'Kelola Data Buku']);
    })->name('books');
    
    // members management pages
    Route::get('/members', function () {
        return view('pages.members.manage', ['title' => 'Kelola Data Anggota']);
    })->name('members');
    
    // borrowing and returns management pages
    Route::get('/borrowing-returns', function () {
        return view('pages.borrowing-returns.manage', ['title' => 'Kelola Peminjaman & Pengembalian']);
    })->name('borrowing-returns');
    
    // reports pages
    Route::get('/reports', function () {
        return view('pages.reports.index', ['title' => 'Laporan']);
    })->name('reports');
});

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('pages.auth.signin', ['title' => 'Login']);
})->name('login');

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::post('/signup', [App\Http\Controllers\AuthController::class, 'register'])->name('signup.post');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// authentication pages
Route::get('/login', function () {
    return view('pages.auth.signin', ['title' => 'Login']);
})->name('login');

Route::get('/register', function () {
    return view('pages.auth.signup', ['title' => 'Register']);
})->name('register');

// Password reset routes
Route::get('/forgot-password', [App\Http\Controllers\AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\AuthController::class, 'sendPasswordResetLink'])->name('password.email');
Route::get('/reset-password', [App\Http\Controllers\AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/reset-password', [App\Http\Controllers\AuthController::class, 'resetPassword'])->name('password.reset');






















