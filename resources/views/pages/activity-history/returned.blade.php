@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Riwayat Pengembalian" />

<div class="space-y-6">
    <x-common.component-card title="Riwayat Pengembalian Buku">
        <form method="GET" class="mb-4 flex gap-4">
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            <span class="self-center">s/d</span>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('activity.returned') }}" class="btn btn-secondary">Reset</a>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Peminjam</th>
                        <th class="px-4 py-3 text-left font-semibold">Buku</th>
                        <th class="px-4 py-3 text-left font-semibold">Tgl Kembali</th>
                        <th class="px-4 py-3 text-left font-semibold">Denda</th>
                        <th class="px-4 py-3 text-left font-semibold">Status Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowings as $index => $b)
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $b->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $b->book->title ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $b->returned_at ? $b->returned_at->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($b->fine ?? 0, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            @if($b->fine_status == 'paid')
                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">Lunas</span>
                            @elseif($b->fine_status == 'unpaid' && $b->fine > 0)
                            <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">Belum Lunas</span>
                            @else
                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">Belum ada pengembalian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-common.component-card>
</div>
@endsection