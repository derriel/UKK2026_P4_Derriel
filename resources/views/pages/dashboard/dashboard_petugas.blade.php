@extends('layouts.petugas-layout')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Petugas</h1>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Ringkasan aktivitas perpustakaan dan statistik terkini.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('dashboard_petugas') }}"
                    class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                    <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5l4-4-1.414-1.414L9 10.172V6h-2v6h6v-2h-3.172z" />
                    </svg>
                    Segarkan
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 mb-8">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Buku</p>
                    <h3 class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalBooks }}</h3>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Jumlah koleksi buku saat ini</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6C4 4.89543 4.89543 4 6 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-600 dark:stroke-blue-400"/>
                        <path d="M9 8H15M9 12H15M9 16H13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-blue-600 dark:stroke-blue-400"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Peminjaman Aktif</p>
                    <h3 class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalBorrowed }}</h3>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Jumlah peminjaman yang belum dikembalikan</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="stroke-green-600 dark:stroke-green-400"/>
                        <path d="M12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20Z" stroke="currentColor" stroke-width="2" class="stroke-green-600 dark:stroke-green-400"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Anggota Terdaftar</p>
                    <h3 class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalMembers }}</h3>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Jumlah anggota perpustakaan</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900/30">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4.5C8.41015 4.5 5.5 7.41015 5.5 11C5.5 14.5899 8.41015 17.5 12 17.5C15.5899 17.5 18.5 14.5899 18.5 11C18.5 7.41015 15.5899 4.5 12 4.5Z" stroke="currentColor" stroke-width="2" class="stroke-yellow-600 dark:stroke-yellow-400"/>
                        <path d="M12 13.5C10.6193 13.5 9.5 12.3807 9.5 11C9.5 9.61929 10.6193 8.5 12 8.5C13.3807 8.5 14.5 9.61929 14.5 11C14.5 12.3807 13.3807 13.5 12 13.5Z" stroke="currentColor" stroke-width="2" class="stroke-yellow-600 dark:stroke-yellow-400"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
        