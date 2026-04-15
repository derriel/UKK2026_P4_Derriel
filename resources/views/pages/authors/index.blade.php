@extends('layouts.app')
@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Kelola Data Pengarang" />

<div class="space-y-6">
    <x-common.component-card title="Daftar Data Pengarang">
        <div class="flex justify-end mb-4">
            <a href="{{ route('authors.create') }}" class="btn btn-primary">
                + Tambah Pengarang
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
                        <th class="px-4 py-3 text-left font-semibold">Nama Pengarang</th>
                        <th class="px-4 py-3 text-left font-semibold">Email</th>
                        <th class="px-4 py-3 text-left font-semibold">Tanggal Lahir</th>
                        <th class="px-4 py-3 text-left font-semibold">Kewarganegaraan</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($authors as $index => $author)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $author->name }}</td>
                        <td class="px-4 py-3">{{ $author->email ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $author->birth_date ? \Carbon\Carbon::parse($author->birth_date)->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-3">{{ $author->nationality ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('authors.edit', $author->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('authors.destroy', $author->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada data pengarang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-common.component-card>
</div>
@endsection