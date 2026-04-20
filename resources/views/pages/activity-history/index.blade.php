@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Riwayat Aktivitas" />

<div class="space-y-6">
    <x-common.component-card title="Statistik">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4">
                <p class="text-sm text-blue-600 dark:text-blue-400">Total Dipinjam</p>
                <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $stats['total_borrowed'] }}</p>
            </div>
            <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4">
                <p class="text-sm text-green-600 dark:text-green-400">Total Dikembalikan</p>
                <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $stats['total_returned'] }}</p>
            </div>
            <div class="bg-red-50 dark:bg-red-900/30 rounded-lg p-4">
                <p class="text-sm text-red-600 dark:text-red-400">Denda Belum Lunas</p>
                <p class="text-2xl font-bold text-red-700 dark:text-red-300">Rp {{ number_format($stats['total_fines'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-yellow-50 dark:bg-yellow-900/30 rounded-lg p-4">
                <p class="text-sm text-yellow-600 dark:text-yellow-400">Denda Sudah Lunas</p>
                <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-300">Rp {{ number_format($stats['total_fines_collected'], 0, ',', '.') }}</p>
            </div>
        </div>
    </x-common.component-card>

    <x-common.component-card title="Riwayat Peminjaman">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Peminjam</th>
                        <th class="px-4 py-3 text-left font-semibold">Buku</th>
                        <th class="px-4 py-3 text-left font-semibold">Tgl Pinjam</th>
                        <th class="px-4 py-3 text-left font-semibold">Tgl Jatuh Tempo</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowings->take(10) as $index => $b)
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $b->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $b->book->title ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $b->borrow_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $b->due_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            @if($b->status == 'borrowed')
                            <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-700">Dipinjam</span>
                            @elseif($b->status == 'returned')
                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">Dikembalikan</span>
                            @else
                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">{{ $b->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">Belum ada peminjaman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ route('activity.borrowed') }}" class="text-blue-600 hover:underline">Lihat Semua Peminjaman →</a>
        </div>
    </x-common.component-card>

    <x-common.component-card title="Riwayat Denda">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Peminjam</th>
                        <th class="px-4 py-3 text-left font-semibold">Buku</th>
                        <th class="px-4 py-3 text-left font-semibold">Jumlah</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fines->take(10) as $index => $fine)
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $fine->borrowing->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $fine->borrowing->book->title ?? '-' }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            @if($fine->status == 'paid')
                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">Lunas</span>
                            @else
                            <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">Belum Lunas</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center text-gray-500">Belum ada denda.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ route('activity.fines') }}" class="text-blue-600 hover:underline">Lihat Semua Denda →</a>
        </div>
    </x-common.component-card>
</div>
@endsection