@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Kelola Data Penerbit" />

<div class="space-y-6">
    <x-common.component-card title="Daftar Data Penerbit">
        <div class="flex justify-end mb-4">
            <a href="{{ route('publishers.create') }}" class="btn btn-primary">
                + Tambah Penerbit
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
                        <th class="px-4 py-3 text-left font-semibold">Logo</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama Penerbit</th>
                        <th class="px-4 py-3 text-left font-semibold">Kota</th>
                        <th class="px-4 py-3 text-left font-semibold">Telepon</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($publishers as $index => $publisher)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">
                            @if($publisher->logo)
                            <img src="{{ asset('storage/' . $publisher->logo) }}" alt="Logo" class="w-10 h-10 object-cover rounded">
                            @else
                            <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded"></div>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $publisher->name }}</td>
                        <td class="px-4 py-3">{{ $publisher->city ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $publisher->phone ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('publishers.edit', $publisher->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('publishers.destroy', $publisher->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada data penerbit.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-common.component-card>
</div>
@endsection