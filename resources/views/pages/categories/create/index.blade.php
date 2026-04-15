@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Tambah Data Kategori" />

<div class="space-y-6">
    <x-common.component-card title="Form Tambah Kategori">
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Kategori *</label>
                <input name="name" type="text" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2 pt-4">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </x-common.component-card>
</div>
@endsection