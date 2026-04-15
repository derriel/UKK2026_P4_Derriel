@extends('layouts.petugas-layout')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    <!-- Bagian Header Halaman Dashboard -->
    <div class="mb-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Petugas</h1>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Ringkasan aktivitas perpustakaan dan statistik terkini.</p>
            </div>
            <!-- Tombol Segarkan Halaman -->
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('dashboard_petugas') }}"
                    class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                    <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5l4-4-1.414-1.414L9 10.172V6h-2v6h6v-2h-3.172z" />
                    </svg>
                    Segarkan
                </a>
            </div>
        </div>
    </div>

    <!-- Bagian Statistik Utama (4 Kolom) -->
    <!-- Menampilkan: Total Buku, Peminjaman Aktif, Pengajuan Pinjaman, Pengajuan Pengembalian -->
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-4 mb-8">
        
        <!-- Card 1: Total Buku -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Buku</p>
                    <h3 class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalBooks }}</h3>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Jumlah koleksi buku saat ini</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6C4 4.89543 4.89543 4 6 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-600 dark:stroke-blue-400"/>
                        <path d="M9 8H15M9 12H15M9 16H13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-600 dark:stroke-blue-400"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 2: Peminjaman Aktif -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Peminjaman Aktif</p>
                    <h3 class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalBorrowed }}</h3>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Jumlah peminjaman yang belum dikembalikan</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-green-600 dark:stroke-green-400"/>
                        <path d="M12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20Z" stroke="currentColor" stroke-width="2" class="stroke-green-600 dark:stroke-green-400"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 3: Pengajuan Pinjaman -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengajuan Pinjaman</p>
                    <h3 class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalBorrowRequests }}</h3>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Permintaan peminjaman yang menunggu aksi</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V11L15 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-600 dark:stroke-blue-400" />
                        <path d="M19 12C19 16.4183 15.4183 20 11 20C6.58172 20 3 16.4183 3 12C3 7.58172 6.58172 4 11 4C12.6582 4 14.2321 4.59752 15.423 5.59342" stroke="currentColor" stroke-width="2" class="stroke-blue-600 dark:stroke-blue-400" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 4: Pengajuan Pengembalian -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengajuan Pengembalian</p>
                    <h3 class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalReturnRequests }}</h3>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Pengembalian buku yang menunggu konfirmasi</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-violet-100 dark:bg-violet-900/30">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 7L12 11L16 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-violet-600 dark:stroke-violet-400" />
                        <path d="M5 17V15C5 12.7909 6.79086 11 9 11H15C17.2091 11 19 12.7909 19 15V17" stroke="currentColor" stroke-width="2" class="stroke-violet-600 dark:stroke-violet-400" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Kelola Peminjaman & Pengembalian -->
    <!-- Tabel untuk menyetujui/menolak pengajuan pinjam dan kembali -->
    <div class="mt-8 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Kelola Peminjaman & Pengembalian</h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Setetujui pengajuan peminjaman dan pengembalian buku.</p>
            </div>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-600 dark:divide-gray-700 dark:text-gray-300">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Nama User</th>
                        <th class="px-4 py-3">Buku</th>
                        <th class="px-4 py-3">Tanggal Pinjam</th>
                        <th class="px-4 py-3">Jatuh Tempo</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                        $borrowings = \App\Models\Borrowing::with(['user', 'book'])->whereIn('status', ['requested', 'return_requested'])->orderBy('created_at', 'desc')->limit(10)->get();
                    @endphp
                    @forelse($borrowings as $borrowing)
                    <tr>
                        <td class="px-4 py-4 text-gray-900 dark:text-white">{{ $borrowing->user->name ?? '-' }}</td>
                        <td class="px-4 py-4">{{ $borrowing->book->title ?? '-' }}</td>
                        <td class="px-4 py-4">{{ $borrowing->borrow_date ? \Carbon\Carbon::parse($borrowing->borrow_date)->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-4">{{ $borrowing->due_date ? \Carbon\Carbon::parse($borrowing->due_date)->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-4">
                            @if($borrowing->status === 'requested')
                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300">
                                Menunggu Pinjaman
                            </span>
                            @elseif($borrowing->status === 'return_requested')
                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                                Menunggu Pengembalian
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex gap-2">
                                @if($borrowing->status === 'requested')
                                <form action="{{ route('borrowing-returns.approveBorrow', $borrowing->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Setujui peminjaman ini?')">
                                        Setuju
                                    </button>
                                </form>
                                @elseif($borrowing->status === 'return_requested')
                                <form action="{{ route('borrowing-returns.approveReturn', $borrowing->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui pengembalian ini?')">
                                        Setuju
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="px-4 py-6 text-center text-gray-500 dark:text-gray-400" colspan="6">
                            Tidak ada pengajuan peminjaman atau pengembalian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bagian Data Siswa -->
    <!-- Menampilkan daftar siswa dengan status online/offline -->
    <div class="mt-8 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Data Siswa</h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Menampilkan data siswa dengan status online/offline.</p>
            </div>
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
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="siswa-tbody">
                    @forelse ($memberStatuses as $member)
                    <tr>
                        <td class="px-4 py-4 text-gray-900 dark:text-white">{{ $member['name'] }}</td>
                        <td class="px-4 py-4">{{ $member['email'] }}</td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold
{{ $member['status'] === 'Online'
    ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
    : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">

                                <span class="w-2 h-2 rounded-full
    {{ $member['status'] === 'Online' ? 'bg-green-400' : 'bg-gray-400' }}"></span>

                                {{ $member['status'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="px-4 py-6 text-center text-gray-500 dark:text-gray-400" colspan="3">
                            Belum ada data siswa yang tersedia.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
        