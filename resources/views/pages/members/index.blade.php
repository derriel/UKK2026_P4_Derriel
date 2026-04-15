@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Kelola Data Siswa" />

<div class="space-y-6">
    <x-common.component-card title="Daftar Data Siswa">
        <div class="flex justify-end mb-4">
            <a href="{{ route('members.create') }}" class="btn btn-primary">
                + Tambah Siswa
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
                        <th class="px-4 py-3 text-left font-semibold">NIS</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold">Email</th>
                        <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                        <th class="px-4 py-3 text-left font-semibold">Jurusan</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $index => $s)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $s->nis ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $s->name }}</td>
                        <td class="px-4 py-3">{{ $s->email }}</td>
                        <td class="px-4 py-3">{{ $s->kelas ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $s->jurusan ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('members.edit', $s->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('members.destroy', $s->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada siswa.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-common.component-card>
</div>
@endsection