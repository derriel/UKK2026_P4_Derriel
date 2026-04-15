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
                        <a href="{{ route('member.books.index') }}" class="text-sm font-medium text-orange-600">Buku</a>
                        <a href="{{ route('member.borrowings.index') }}" class="text-sm font-medium text-gray-700 hover:text-orange-600">Pinjaman Saya</a>
                    </nav>
                </div>

                <div class="flex-1 max-w-xl mx-4">
                    <form action="{{ route('member.books.index') }}" method="GET" class="relative">
                        <input type="text" name="search" placeholder="Cari judul buku, pengarang, ISBN..." value="{{ request('search') }}" class="w-full h-10 pl-4 pr-12 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 h-8 w-8 flex items-center justify-center rounded bg-orange-600 text-white hover:bg-orange-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                    <div class="h-8 w-8 rounded-full bg-gray-200 overflow-hidden">
                        @if(Auth::user()->photo)
                        <img src="{{ Storage::url(Auth::user()->photo) }}" alt="User" class="h-full w-full object-cover">
                        @else
                        <div class="h-full w-full flex items-center justify-center">
                            <i class="fa-regular fa-circle-user text-lg text-gray-500"></i>
                        </div>
                        @endif
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Katalog Buku</h1>
                <p class="text-gray-600">Temukan buku favoritmu</p>
            </div>
            <a href="{{ route('welcome') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Kembali</a>
        </div>

        @if(request('search'))
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600 mb-4">Hasil pencarian untuk "{{ request('search') }}" - {{ $books->count() }} buku ditemukan</p>
                <a href="{{ route('member.books.index') }}" class="text-sm text-orange-600 hover:text-orange-700">Hapus filter</a>
            </div>
        @elseif($books->count() > 0)
            <p class="text-sm text-gray-600 mb-4">Menampilkan {{ $books->count() }} buku</p>
        @endif
    </main>

    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 pb-12">
        @if($books->count() > 0)
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
            @foreach($books as $book)
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
                        <span class="absolute top-2 right-2 px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded">{{ $book->stock }} tersedia</span>
                        @else
                        <span class="absolute top-2 right-2 px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded">Habis</span>
                        @endif
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
            <i class="bi bi-book text-4xl text-gray-400"></i>
            <p class="mt-4 text-gray-600">Buku tidak ditemukan</p>
        </div>
        @endif
    </div>

    <footer class="bg-gray-900 py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} PerpustakaanKu - SMK MVP ARS Internasional</p>
        </div>
    </footer>
</div>
@endsection