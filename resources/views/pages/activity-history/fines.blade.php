@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Riwayat Denda" />

<div class="space-y-6">
    <x-common.component-card title="Riwayat Denda">
        <form method="GET" class="mb-4 flex gap-4">
            <select name="status" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                <option value="all">Semua Status</option>
                <option value="unpaid">Belum Lunas</option>
                <option value="paid">Lunas</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            <span class="self-center">s/d</span>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('activity.fines') }}" class="btn btn-secondary">Reset</a>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Peminjam</th>
                        <th class="px-4 py-3 text-left font-semibold">Buku</th>
                        <th class="px-4 py-3 text-left font-semibold">Jumlah Denda</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-left font-semibold">Tgl Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fines as $index => $fine)
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
                        <td class="px-4 py-3">{{ $fine->paid_at ? $fine->paid_at->format('d/m/Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">Belum ada denda.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-common.component-card>
</div>
@endsection