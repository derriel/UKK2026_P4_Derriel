@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Edit Peminjaman" />

<div class="space-y-6">
    <x-common.component-card title="Form Edit Peminjaman">
        <form action="{{ route('borrowing-returns.update', $borrowing->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Anggota *</label>
                    <select name="user_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                        <option value="">-- Pilih Anggota --</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $borrowing->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Buku *</label>
                    <select name="book_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                        <option value="">-- Pilih Buku --</option>
                        @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ $borrowing->book_id == $book->id ? 'selected' : '' }}>{{ $book->title }}</option>
                        @endforeach
                    </select>
                    @error('book_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role *</label>
                    <select name="role_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $borrowing->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                        <option value="requested" {{ $borrowing->status == 'requested' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="borrowed" {{ $borrowing->status == 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="return_requested" {{ $borrowing->status == 'return_requested' ? 'selected' : '' }}>Pengembalian Diajukan</option>
                        <option value="returned" {{ $borrowing->status == 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="overdue" {{ $borrowing->status == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Peminjaman *</label>
                    <input name="borrow_date" type="date" value="{{ old('borrow_date', $borrowing->borrow_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    @error('borrow_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Jatuh Tempo *</label>
                    <input name="due_date" type="date" value="{{ old('due_date', $borrowing->due_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Kembali (Direncanakan)</label>
                    <input name="return_date" type="date" value="{{ old('return_date', $borrowing->return_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <div class="flex gap-2 pt-4">
                <a href="{{ route('borrowing-returns.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </x-common.component-card>
</div>
@endsection