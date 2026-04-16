@extends('layouts.fullscreen-layout')
@use('Illuminate\Support\Facades\Storage')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                <div class="flex items-center gap-8">
                    <a href="{{ route('welcome') }}" class="text-2xl font-bold text-gray-900">PerpustakaanKu</a>
                    <nav class="hidden md:flex items-center gap-6">
                        <a href="{{ route('member.books.index') }}" class="text-sm font-medium text-gray-700 hover:text-orange-600">Buku</a>
                        <a href="{{ route('member.borrowings.index') }}" class="text-sm font-medium text-gray-700 hover:text-orange-600">Pinjaman Saya</a>
                    </nav>
                </div>

                <div class="flex-1 max-w-xl mx-4">
                    <form action="{{ route('member.books.index') }}" method="GET" class="relative">
                        <input type="text" name="search" placeholder="Cari judul buku, pengarang, ISBN..." value="{{ request('search') }}" class="w-full h-10 pl-4 pr-12 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 h-8 w-8 flex items-center justify-center rounded bg-orange-600 text-white hover:bg-orange-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-full bg-gray-200 overflow-hidden">
                            @if(Auth::user()->photo)
                            <img src="{{ Storage::url(Auth::user()->photo) }}" alt="User" class="h-full w-full object-cover">
                            @else
                            <div class="h-full w-full flex items-center justify-center">
                                <i class="fa-regular fa-circle-user text-lg text-gray-500"></i>
                            </div>
                            @endif
                        </div>
                        <span class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Selamat datang, {{ auth()->user()->name }}</h1>
            <p class="mt-1 text-gray-600">Temukan buku favoritmu dari koleksi kami</p>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <a href="{{ route('member.books.index') }}" class="group relative overflow-hidden rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 p-6 hover:shadow-lg transition-shadow">
                <div class="absolute right-0 bottom-0 opacity-20">
                    <i class="bi bi-book text-9xl text-white"></i>
                </div>
                <div class="relative">
                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-white/20 text-white mb-4">
                        <i class="bi bi-book text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Koleksi Buku</h3>
                    <p class="mt-1 text-orange-100">Jelajahi katalog lengkap buku-buku pilihan</p>
                    <span class="inline-flex items-center gap-1 mt-3 text-sm font-medium text-white group-hover:underline">
                        Lihat Selengkapnya <i class="bi bi-arrow-right"></i>
                    </span>
                </div>
            </a>

            <a href="{{ route('member.borrowings.index') }}" class="group relative overflow-hidden rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 p-6 hover:shadow-lg transition-shadow">
                <div class="absolute right-0 bottom-0 opacity-20">
                    <i class="bi bi-journal-check text-9xl text-white"></i>
                </div>
                <div class="relative">
                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-white/20 text-white mb-4">
                        <i class="bi bi-journal-check text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Peminjaman</h3>
                    <p class="mt-1 text-blue-100">{{ $activeBorrowings ?? 0 }} buku sedang dipinjam</p>
                    <span class="inline-flex items-center gap-1 mt-3 text-sm font-medium text-white group-hover:underline">
                        Lihat Selengkapnya <i class="bi bi-arrow-right"></i>
                    </span>
                </div>
            </a>

            <a href="{{ route('member.profile') }}" class="group relative overflow-hidden rounded-xl bg-gradient-to-r from-purple-500 to-purple-600 p-6 hover:shadow-lg transition-shadow">
                <div class="absolute right-0 bottom-0 opacity-20">
                    <i class="bi bi-person text-9xl text-white"></i>
                </div>
                <div class="relative">
                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-white/20 text-white mb-4">
                        <i class="bi bi-person text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Profil</h3>
                    <p class="mt-1 text-purple-100">Kelola informasi akunmu</p>
                    <span class="inline-flex items-center gap-1 mt-3 text-sm font-medium text-white group-hover:underline">
                        Lihat Selengkapnya <i class="bi bi-arrow-right"></i>
                    </span>
                </div>
            </a>
        </div>

        @if(isset($overdueBorrowings) && $overdueBorrowings->count() > 0)
        <div class="mb-10 rounded-xl border border-red-200 bg-red-50 p-6">
            <div class="flex items-center gap-2 mb-4">
                <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl"></i>
                <h2 class="text-lg font-bold text-red-700">Buku Terlambat</h2>
            </div>
            <p class="text-sm text-red-600 mb-4">Anda memiliki {{ $overdueBorrowings->count() }} buku yang terlambat dikembalikan. Silakan kembalikan segera untuk menghindari denda.</p>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($overdueBorrowings as $borrowing)
                <div class="bg-white rounded-lg border border-red-200 p-4">
                    <h3 class="font-semibold text-gray-900 line-clamp-1">{{ $borrowing->book->title ?? '-' }}</h3>
                    <p class="text-sm text-red-600 mt-1">Jatuh tempo: {{ \Carbon\Carbon::parse($borrowing->due_date)->format('d/m/Y') }}</p>
                    <p class="text-sm text-gray-500">Terlambat: {{ (int)now()->diffInDays($borrowing->due_date) }} hari</p>
                    <a href="{{ route('member.borrowings.index') }}" class="inline-block mt-3 text-sm font-medium text-red-600 hover:text-red-700">Lihat Detail</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Latest Books -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Buku Terbaru</h2>
                <a href="{{ route('member.books.index') }}" class="text-sm font-medium text-orange-600 hover:text-orange-700">Lihat Semua</a>
            </div>
        </div>

        @if(isset($recentBooks) && $recentBooks->count() > 0)
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
            @foreach($recentBooks as $book)
            <a href="{{ route('member.books.show', $book) }}" class="group">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="aspect-[3/4] bg-gray-100 relative">
                        @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
                        @else
                        <div class="flex h-full items-center justify-center bg-gray-200">
                            <i class="bi bi-book text-4xl text-gray-400"></i>
                        </div>
                        @endif
                        @if($book->stock > 0)
                        <span class="absolute top-2 right-2 px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded">Tersedia</span>
                        @else
                        <span class="absolute top-2 right-2 px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded">Habis</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-medium text-gray-900 line-clamp-2 group-hover:text-orange-600">{{ $book->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ optional($book->author)->name ?? '-' }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ optional($book->publisher)->name ?? '-' }}</p>
                        @if(optional($book->rack)->name)
                        <p class="text-xs text-gray-400">Rak: {{ $book->rack->name }}</p>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="bg-gray-50 rounded-lg p-12 text-center">
            <i class="bi bi-book text-4xl text-gray-400"></i>
            <p class="mt-4 text-gray-600">Belum ada buku terbaru</p>
        </div>
        @endif

        <!-- Popular Books / Buku Terlaris -->
        <div class="mt-12 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Buku Terlaris</h2>
                <a href="{{ route('member.books.index') }}" class="text-sm font-medium text-orange-600 hover:text-orange-700">Lihat Semua</a>
            </div>
        </div>

        @if(isset($popularBooks) && $popularBooks->count() > 0)
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
            @foreach($popularBooks as $book)
            <a href="{{ route('member.books.show', $book) }}" class="group">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="aspect-[3/4] bg-gray-100 relative">
                        @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
                        @else
                        <div class="flex h-full items-center justify-center bg-gray-200">
                            <i class="bi bi-book text-4xl text-gray-400"></i>
                        </div>
                        @endif
                        @if($book->stock > 0)
                        <span class="absolute top-2 right-2 px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded">Tersedia</span>
                        @else
                        <span class="absolute top-2 right-2 px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded">Habis</span>
                        @endif
                        <div class="absolute top-2 left-2">
                            <span class="px-2 py-0.5 text-xs font-medium bg-orange-500 text-white rounded">
                                <i class="bi bi-fire mr-1"></i>{{ $book->borrowings_count ?? 0 }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-medium text-gray-900 line-clamp-2 group-hover:text-orange-600">{{ $book->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ optional($book->author)->name ?? '-' }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="bg-gray-50 rounded-lg p-12 text-center">
            <i class="bi bi-bar-chart text-4xl text-gray-400"></i>
            <p class="mt-4 text-gray-600">Belum ada data buku terlaris</p>
        </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 py-12 mt-16">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid gap-8 md:grid-cols-3">
                <div>
                    <h3 class="text-lg font-semibold text-white">PerpustakaanKu</h3>
                    <p class="mt-2 text-sm text-gray-400">Aplikasi perpustakaan digital untuk anggota SMK MVP ARS Internasional Bandung.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Kontak</h3>
                    <div class="mt-2 space-y-2 text-sm text-gray-400">
                        <p>Jl. Gegerkalong Hilir No. 69, Bandung</p>
                        <p>(022) 201 8730</p>
                        <p>perpustakaan@smkmvparsi.sch.id</p>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Jam Buka</h3>
                    <p class="mt-2 text-sm text-gray-400">Senin - Jumat: 07:00 - 16:00</p>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-800 text-center">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} PerpustakaanKu - SMK MVP ARS Internasional Bandung</p>
            </div>
        </div>
    </footer>
</div>
@endsection