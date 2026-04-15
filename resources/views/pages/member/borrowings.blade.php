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
                        <a href="{{ route('member.borrowings.index') }}" class="text-sm font-medium text-orange-600">Pinjaman Saya</a>
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
                <h1 class="text-2xl font-bold text-gray-900">Riwayat Peminjaman</h1>
                <p class="text-gray-600">Monitor status pinjamanmu</p>
            </div>
            <a href="{{ route('welcome') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Kembali</a>
        </div>

        <!-- Stats -->
        <div class="grid gap-4 sm:grid-cols-4 mb-8">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ $borrowings->count() }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">Dipinjam</p>
                <p class="text-2xl font-bold text-gray-900">{{ $borrowings->whereIn('status', ['requested', 'borrowed'])->count() }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">Proses</p>
                <p class="text-2xl font-bold text-gray-900">{{ $borrowings->whereIn('status', ['return_requested'])->count() }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">Dikembalikan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $borrowings->where('status', 'returned')->count() }}</p>
            </div>
        </div>

        <!-- Borrowings List -->
        @if($borrowings->count() > 0)
        <div class="space-y-4">
            @foreach($borrowings as $borrowing)
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center gap-4">
                    <div class="h-20 w-14 bg-gray-100 rounded overflow-hidden flex-shrink-0">
                        @if(optional($borrowing->book)->cover_image)
                        <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" alt="{{ $borrowing->book->title }}" class="h-full w-full object-cover">
                        @else
                        <div class="h-full w-full flex items-center justify-center">
                            <i class="bi bi-book text-xl text-gray-400"></i>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900">{{ $borrowing->book->title ?? 'Buku tidak tersedia' }}</h3>
                        <p class="text-sm text-gray-500">{{ optional($borrowing->book)->author->name ?? '-' }}</p>
                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                            <span>Pinjam: {{ optional($borrowing->borrow_date)->format('d M') ?? '-' }}</span>
                            <span>Kembali: {{ optional($borrowing->due_date)->format('d M Y') ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        @if($borrowing->status === 'borrowed')
                        <span class="px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Dipinjam</span>
                        @elseif($borrowing->status === 'requested')
                        <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Menunggu</span>
                        @elseif($borrowing->status === 'return_requested')
                        <span class="px-3 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">Proses Kembali</span>
                        @elseif($borrowing->status === 'returned')
                        <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Dikembalikan</span>
                        @elseif($borrowing->status === 'overdue')
                        <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Terlambat</span>
                        @endif

                        @if($borrowing->status === 'borrowed')
                        <form action="{{ route('member.borrowings.return', $borrowing) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700">Kembalikan</button>
                        </form>
                        @else
                        <a href="{{ route('member.books.show', $borrowing->book) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Detail</a>
                        @endif

                        @if($borrowing->status === 'returned' && $borrowing->fine_status === 'unpaid' && $borrowing->fine > 0)
                        <div class="flex flex-col items-end gap-2 mt-2">
                            <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                <i class="bi bi-exclamation-triangle mr-1"></i>Denda: Rp {{ number_format($borrowing->fine, 0, ',', '.') }}
                            </span>
                            <form action="{{ route('member.borrowings.pay-fine', $borrowing) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">Bayar Denda</button>
                            </form>
                        </div>
                        @endif

                        @if($borrowing->status === 'returned' && $borrowing->fine_status === 'paid' && $borrowing->fine > 0)
                        <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                            <i class="bi bi-check-circle mr-1"></i>Denda Lunas
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-50 rounded-lg p-12 text-center">
            <i class="bi bi-journal-x text-4xl text-gray-400"></i>
            <p class="mt-4 text-gray-600">Belum ada peminjaman</p>
        </div>
        @endif
    </main>

    <footer class="bg-gray-900 py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} PerpustakaanKu - SMK MVP ARS Internasional</p>
        </div>
    </footer>
</div>
@endsection