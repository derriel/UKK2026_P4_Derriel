@extends('layouts.app')

@section('content')
<!-- ============================================
     DASHBOARD ADMIN - Perpustakaan
     Menampilkan statistik lengkap untuk admin
============================================= -->
@php
$chartLabels = collect($topBorrowedBooks)->pluck('title')->toJson();
$chartValues = collect($topBorrowedBooks)->pluck('borrow_count')->toJson();
@endphp

<!-- Bagian Statistik Utama -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
  <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Buku</p>
        <h3 class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalBooks }}</h3>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Jumlah koleksi buku saat ini</p>
      </div>
      <div class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900/30">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6C4 4.89543 4.89543 4 6 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-600 dark:stroke-blue-400" />
          <path d="M9 8H15M9 12H15M9 16H13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-600 dark:stroke-blue-400" />
        </svg>
      </div>
    </div>
  </div>

  <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Buku Dipinjam</p>
        <h3 class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalBorrowed }}</h3>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Buku yang belum dikembalikan</p>
      </div>
      <div class="flex items-center justify-center w-14 h-14 rounded-full bg-yellow-100 dark:bg-yellow-900/30">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" class="stroke-yellow-600 dark:stroke-yellow-400" />
          <path d="M12 7V12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="stroke-yellow-600 dark:stroke-yellow-400" />
        </svg>
      </div>
    </div>
  </div>

  <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Anggota</p>
        <h3 class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalMembers }}</h3>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Akun anggota terdaftar</p>
      </div>
      <div class="flex items-center justify-center w-14 h-14 rounded-full bg-green-100 dark:bg-green-900/30">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="9" cy="10" r="3" stroke="currentColor" stroke-width="2" class="stroke-green-600 dark:stroke-green-400" />
          <path d="M4 20C4 16.6863 7.58172 14 12 14C16.4183 14 20 16.6863 20 20" stroke="currentColor" stroke-width="2" class="stroke-green-600 dark:stroke-green-400" />
        </svg>
      </div>
    </div>
  </div>

  <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Akun Aktif</p>
        <h3 class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $activeAccounts }}</h3>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Akun yang sedang online</p>
      </div>
      <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-100 dark:bg-red-900/30">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" class="stroke-red-600 dark:stroke-red-400" />
          <circle cx="12" cy="12" r="3" fill="currentColor" class="fill-red-600 dark:fill-red-400" />
        </svg>
      </div>
    </div>
  </div>
</div>

<!-- Bagian Statistik Tambahan (Pengajuan) -->
<!-- Card 5: Pengajuan Pinjaman, Card 6: Pengajuan Pengembalian -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6 mb-8">
  <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengajuan Pinjaman</p>
        <h3 class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalBorrowRequests }}</h3>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Permintaan peminjaman yang belum disetujui</p>
      </div>
      <div class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900/30">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12 5V11L15 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-600 dark:stroke-blue-400" />
          <path d="M19 12C19 16.4183 15.4183 20 11 20C6.58172 20 3 16.4183 3 12C3 7.58172 6.58172 4 11 4C12.6582 4 14.2321 4.59752 15.423 5.59342" stroke="currentColor" stroke-width="2" class="stroke-blue-600 dark:stroke-blue-400" />
        </svg>
      </div>
    </div>
  </div>
  <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengajuan Pengembalian</p>
        <h3 class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalReturnRequests }}</h3>
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Pengembalian yang menunggu konfirmasi</p>
      </div>
      <div class="flex items-center justify-center w-14 h-14 rounded-full bg-violet-100 dark:bg-violet-900/30">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M8 7L12 11L16 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-violet-600 dark:stroke-violet-400" />
          <path d="M5 17V15C5 12.7909 6.79086 11 9 11H15C17.2091 11 19 12.7909 19 15V17" stroke="currentColor" stroke-width="2" class="stroke-violet-600 dark:stroke-violet-400" />
        </svg>
      </div>
    </div>
  </div>
</div>

<!-- Bagian Grafik Peminjaman -->
<!-- Menampilkan grafik buku paling sering dipinjam -->
<div class="grid grid-cols-1 xl:grid-cols-8 gap-4 md:gap-6">
  <div class="xl:col-span-8 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
    <div class="flex items-center justify-between gap-4">
      <div>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik Peminjaman Buku Terbanyak</h2>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Menampilkan buku paling sering dipinjam berdasarkan data peminjaman.</p>
      </div>
      <button class="btn btn-primary inline-flex items-center gap-2">
        <span>Refresh</span>
      </button>
    </div>
    <div class="mt-6">
      <div id="topBooksChart" data-labels='{{ $chartLabels }}' data-values='{{ $chartValues }}'></div>
    </div>
  </div>

</div>
@endsection