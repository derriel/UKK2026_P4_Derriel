@extends('layouts.app')

@section('content')
  @php
    $chartLabels = collect($topBorrowedBooks)->pluck('title')->toJson();
    $chartValues = collect($topBorrowedBooks)->pluck('borrow_count')->toJson();
  @endphp

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
            <path d="M6 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6C4 4.89543 4.89543 4 6 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-600 dark:stroke-blue-400"/>
            <path d="M9 8H15M9 12H15M9 16H13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-600 dark:stroke-blue-400"/>
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
            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" class="stroke-yellow-600 dark:stroke-yellow-400"/>
            <path d="M12 7V12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="stroke-yellow-600 dark:stroke-yellow-400"/>
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
            <circle cx="9" cy="10" r="3" stroke="currentColor" stroke-width="2" class="stroke-green-600 dark:stroke-green-400"/>
            <path d="M4 20C4 16.6863 7.58172 14 12 14C16.4183 14 20 16.6863 20 20" stroke="currentColor" stroke-width="2" class="stroke-green-600 dark:stroke-green-400"/>
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
            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" class="stroke-red-600 dark:stroke-red-400"/>
            <circle cx="12" cy="12" r="3" fill="currentColor" class="fill-red-600 dark:fill-red-400"/>
          </svg>
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 xl:grid-cols-8 gap-4 md:gap-6">
    <div class="xl:col-span-5 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
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

    <div class="xl:col-span-3 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
      <div class="flex items-center justify-between gap-4">
        <div>
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Status Akun Pengguna</h2>
          <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Daftar pengguna online dan offline dalam sistem.</p>
        </div>
        <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
          Total ditampilkan {{ count($userStatuses) }}
        </span>
      </div>
      <div class="mt-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-600 dark:divide-gray-700 dark:text-gray-300">
          <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-900 dark:text-gray-400">
            <tr>
              <th class="px-4 py-3">Nama</th>
              <th class="px-4 py-3">Email</th>
              <th class="px-4 py-3">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($userStatuses as $user)
              <tr>
                <td class="px-4 py-4 text-gray-900 dark:text-white">{{ $user['name'] }}</td>
                <td class="px-4 py-4">{{ $user['email'] }}</td>
                <td class="px-4 py-4">
                  <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $user['status'] === 'Online' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                    {{ $user['status'] }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td class="px-4 py-6 text-center text-gray-500 dark:text-gray-400" colspan="3">
                  Belum ada data pengguna yang tersedia.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
