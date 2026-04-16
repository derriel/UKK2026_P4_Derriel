@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Kelola Data Buku" />

<div class="space-y-6">
    <x-common.component-card title="Daftar Data Buku">
        <div class="flex justify-end mb-4">
            <a href="{{ route('books.create') }}" class="btn btn-primary">
                + Tambah Buku
            </a>
        </div>

        @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/30 dark:text-green-200">
            {{ session('success') }}
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Cover</th>
                        <th class="px-4 py-3 text-left font-semibold">Judul Buku</th>
                        <th class="px-4 py-3 text-left font-semibold">Pengarang</th>
                        <th class="px-4 py-3 text-left font-semibold">Penerbit</th>
                        <th class="px-4 py-3 text-left font-semibold">Kategori</th>
                        <th class="px-4 py-3 text-left font-semibold">Rak</th>
                        <th class="px-4 py-3 text-left font-semibold">ISBN</th>
                        <th class="px-4 py-3 text-left font-semibold">Stok</th>
                        <th class="px-4 py-3 text-left font-semibold">Denda/Hari</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $index => $buku)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">
                            @if($buku->cover_image)
                            <img src="{{ asset('storage/' . $buku->cover_image) }}" alt="Cover {{ $buku->title }}" class="w-12 h-16 object-cover rounded-md">
                            @else
                            <div class="w-12 h-16 bg-gray-200 dark:bg-gray-700 rounded-md"></div>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $buku->title }}</td>
                        <td class="px-4 py-3">{{ $buku->author?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $buku->publisher?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $buku->category?->name ?? $buku->category ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $buku->rack?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $buku->isbn }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-brand-100 text-brand-700 dark:bg-brand-900/30 dark:text-brand-300 rounded text-xs font-semibold">
                                {{ $buku->stock }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-red-600 font-medium">Rp {{ number_format($buku->fine_per_day ?? 5000, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($buku->is_active)
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Aktif</span>
                            @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-semibold">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('books.edit', $buku->id) }}" class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>
                                <form action="{{ route('books.destroy', $buku->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada data buku.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-common.component-card>
</div>
@endsection