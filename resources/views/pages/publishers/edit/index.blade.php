@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Data Penerbit" />

<div class="space-y-6">
    <x-common.component-card title="Form Edit Penerbit">
        <form action="{{ route('publishers.update', $publisher->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Penerbit *</label>
                    <input name="name" type="text" value="{{ old('name', $publisher->name) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kota</label>
                    <input name="city" type="text" value="{{ old('city', $publisher->city) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
                    <input name="phone" type="text" value="{{ old('phone', $publisher->phone) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo</label>
                    @if($publisher->logo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $publisher->logo) }}" alt="Logo" class="w-20 h-20 object-cover rounded">
                        </div>
                    @endif
                    <input name="logo" type="file" accept="image/*" class="w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah logo.</p>
                </div>
            </div>

            <div class="flex gap-2 pt-4">
                <a href="{{ route('publishers.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </x-common.component-card>
</div>
@endsection