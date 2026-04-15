@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Kelola Rak Buku (Kategori)" />

<div class="space-y-6">
    <!-- Penjelasan Sistem Rak -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-blue-800 mb-2">
            <i class="bi bi-info-circle mr-1"></i> Sistem Rak Digital
        </h3>
        <p class="text-sm text-blue-700">
            Kategori dalam sistem ini berfungsi sebagai <strong>"Rak"</strong> untuk mengelompokkan buku.
            Setiap buku ditempatkan ke dalam rak tertentu berdasarkan kategorinya.
            Sistem rak digital memudahkan pengguna dalam mencari dan menelusuri koleksi buku.
        </p>
    </div>

    <x-common.component-card title="Daftar Rak Buku (Kategori)">
        <div class="flex justify-end mb-4">
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                + Tambah Kategori
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
                        <th class="px-4 py-3 text-left font-semibold">Nama Kategori</th>
                        <th class="px-4 py-3 text-left font-semibold">Tanggal Dibuat</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $category->name }}</td>
                        <td class="px-4 py-3">{{ $category->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada kategori.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-common.component-card>
</div>
@endsection