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
                        <th class="px-4 py-3 text-left font-semibold">Denda</th>
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
                        <td class="px-4 py-3">{{ optional($peminjaman->returned_at)->format('Y-m-d') ?? optional($peminjaman->return_date)->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @php
                            $displayFine = $peminjaman->fine;
                            $dueDate = \Carbon\Carbon::parse($peminjaman->due_date);
                            $returnDate = $peminjaman->returned_at 
                                ? \Carbon\Carbon::parse($peminjaman->returned_at) 
                                : now();

                            // Calculate fine if late
                            if ($returnDate->greaterThan($dueDate)) {
                                $daysLate = (int)$dueDate->diffInDays($returnDate);
                                $finePerDay = $peminjaman->book->fine_per_day ?? 5000;
                                $displayFine = $daysLate * $finePerDay;
                            }

                            $isPaid = $peminjaman->fine_status === 'paid';
                            @endphp

                            @if($displayFine > 0)
                            <span class="px-2 py-1 {{ $isPaid ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded text-xs font-semibold">
                                Rp {{ number_format($displayFine, 0, ',', '.') }}
                                @if($isPaid)
                                (Lunas)
                                @else
                                (Terlambat)
                                @endif
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
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
                                <form action="{{ route('borrowing-returns.approveBorrow', $peminjaman->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">Setuju</button>
                                </form>
                                @endif
                                @if($peminjaman->status === 'borrowed')
                                <form action="{{ route('borrowing-returns.return', $peminjaman->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi pengembalian buku?')">Kembalikan</button>
                                </form>
                                @endif
                                @if(in_array($peminjaman->status, ['borrowed', 'return_requested', 'returned']) && $peminjaman->fine_status !== 'paid' && $displayFine > 0)
                                <form action="{{ route('borrowing-returns.payFine', $peminjaman->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Tandai denda sudah dibayar?')">Lunas</button>
                                </form>
                                @endif
                                @if($peminjaman->status === 'return_requested')
                                <form action="{{ route('borrowing-returns.approveReturn', $peminjaman->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setuju?')">Setuju</button>
                                </form>
                                <button type="button" class="btn btn-sm btn-danger" onclick="rejectReturn({{ $peminjaman->id }})">Tolak</button>
                                @endif
                                <a href="{{ route('borrowing-returns.edit', $peminjaman->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteBorrowing({{ $peminjaman->id }})">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada data peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-common.component-card>
</div>
@endsection