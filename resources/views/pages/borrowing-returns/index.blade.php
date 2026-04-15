@extends('layouts.app')

@section('content')
<!-- ============================================================
     HALAMAN KELOLA PEMINJAMAN & PENGEMBALIAN (Admin)
     Fitur:
     - Melihat semua data peminjaman
     - Menambah data peminjaman manual
     - Menyetapi/menolak pengajuan pinjam
     - Mengembalikan buku
     - Mengedit dan menghapus data peminjaman
============================================================= -->
<x-common.page-breadcrumb pageTitle="Kelola Peminjaman & Pengembalian" />
<div class="space-y-6">
    <x-common.component-card title="Daftar Peminjaman & Pengembalian">
        
        <!-- Bagian Header: Tombol Tambah dan Statistik -->
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <!-- Tombol: Tambah Peminjaman Manual -->
            <div class="flex items-center">
                <a href="{{ route('borrowing-returns.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-md hover:bg-blue-700 hover:scale-105 active:scale-95 transition">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Peminjaman
                </a>
            </div>

            <!-- Statistik: Peminjaman Aktif, Pengajuan, Pengembalian -->
            <div class="grid gap-4 sm:grid-cols-3 w-full lg:w-auto">

                <!-- Card 1 -->
                <div class="flex items-center gap-4 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 p-5 text-white shadow-lg">
                    <div class="text-3xl">
                        <i class="bi bi-book"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider opacity-80">Peminjaman Aktif</p>
                        <p class="text-3xl font-bold">{{ $totalBorrowed }}</p>
                        <p class="text-xs opacity-80">Sedang dipinjam</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="flex items-center gap-4 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 p-5 text-white shadow-lg">
                    <div class="text-3xl">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider opacity-80">Pengajuan</p>
                        <p class="text-3xl font-bold">{{ $totalBorrowRequests }}</p>
                        <p class="text-xs opacity-80">Menunggu persetujuan</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="flex items-center gap-4 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 p-5 text-white shadow-lg">
                    <div class="text-3xl">
                        <i class="bi bi-arrow-return-left"></i>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider opacity-80">Pengembalian</p>
                        <p class="text-3xl font-bold">{{ $totalReturnRequests }}</p>
                        <p class="text-xs opacity-80">Menunggu konfirmasi</p>
                    </div>
                </div>

            </div>

        </div>

        @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/30 dark:text-green-200">
            {{ session('success') }}
        </div>
        @endif

        <!-- Bagian Tabel Data Peminjaman -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama Anggota</th>
                        <th class="px-4 py-3 text-left font-semibold">Judul Buku</th>
                        <th class="px-4 py-3 text-left font-semibold">Tgl. Peminjaman</th>
                        <th class="px-4 py-3 text-left font-semibold">Tgl. Kembali</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowings as $index => $peminjaman)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $peminjaman->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $peminjaman->book->title ?? '-' }}</td>
                        <td class="px-4 py-3">{{ optional($peminjaman->borrow_date)->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-4 py-3">{{ optional($peminjaman->return_date)->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($peminjaman->status === 'requested')
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 rounded text-xs font-semibold">Menunggu Persetujuan</span>
                            @elseif($peminjaman->status === 'borrowed')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 rounded text-xs font-semibold">Dipinjam</span>
                            @elseif($peminjaman->status === 'return_requested')
                            <span class="px-2 py-1 bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300 rounded text-xs font-semibold">Pengembalian Diajukan</span>
                            @elseif($peminjaman->status === 'returned')
                            <span class="px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 rounded text-xs font-semibold">Dikembalikan</span>
                            @elseif($peminjaman->status === 'overdue')
                            <span class="px-2 py-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 rounded text-xs font-semibold">Terlambat</span>
                            @else
                            <span class="px-2 py-1 bg-slate-100 text-slate-700 dark:bg-slate-900/30 dark:text-slate-300 rounded text-xs font-semibold">{{ ucfirst($peminjaman->status) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2 flex-wrap">
                                @if($peminjaman->status === 'requested')
                                <form action="{{ route('borrowing-returns.approveBorrow', $peminjaman) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary inline-flex items-center gap-2">Setujui Pinjaman</button>
                                </form>
                                @endif
                                @if($peminjaman->status === 'borrowed')
                                <form action="{{ route('borrowing-returns.return', $peminjaman) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    <input type="date" name="returned_at" class="px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded" required>
                                    <button type="submit" class="btn btn-sm btn-success inline-flex items-center gap-2">Kembalikan</button>
                                </form>
                                @endif
                                @if($peminjaman->status === 'return_requested')
                                <form action="{{ route('borrowing-returns.approveReturn', $peminjaman) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success inline-flex items-center gap-2">Setujui Pengembalian</button>
                                </form>
                                @endif
                                <a href="{{ route('borrowing-returns.edit', $peminjaman->id) }}" class="btn btn-sm btn-outline-primary inline-flex items-center gap-2">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.5 2L14 4.5L5 13.5H2.5V11L11.5 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('borrowing-returns.destroy', $peminjaman->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger inline-flex items-center gap-2" onclick="return confirm('Apakah Anda yakin?')">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2 4H14M6.5 7V12M9.5 7V12M3 4L4 14C4 14.5304 4.21071 15.0391 4.58579 15.4142C4.96086 15.7893 5.46957 16 6 16H10C10.5304 16 11.0391 15.7893 11.4142 15.4142C11.7893 15.0391 12 14.5304 12 14L13 4M5.5 4V2.5C5.5 2.23478 5.60536 1.98043 5.79289 1.79289C5.98043 1.60536 6.23478 1.5 6.5 1.5H9.5C9.76522 1.5 10.0196 1.60536 10.2071 1.79289C10.3946 1.98043 10.5 2.23478 10.5 2.5V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada data peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-common.component-card>
</div>
@endsection