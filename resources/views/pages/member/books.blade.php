@extends('layouts.fullscreen-layout')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-sky-600 via-cyan-500 to-indigo-600 text-slate-900 dark:text-slate-100">
    <div class="px-4 py-5 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <header class="flex items-center justify-between gap-4">
            <div>
                <a href="{{ route('welcome') }}" class="text-xl font-bold tracking-tight text-white">PerpustakaanKu</a>
                <p class="mt-1 text-sm text-slate-200">Aplikasi perpustakaan digital untuk anggota</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('member.borrowings.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-900 rounded-full bg-white/90 hover:bg-white">Status Peminjaman</a>
                <div class="relative group" tabindex="0">
                    <button type="button" class="grid h-11 w-11 place-items-center rounded-full bg-white/90 text-slate-900 shadow-lg shadow-slate-950/10 transition hover:bg-white">
                        <i class="fa-regular fa-circle-user"></i>
                    </button>
                    <div class="pointer-events-none absolute right-0 z-20 mt-2 w-48 rounded-3xl bg-slate-950/95 p-2 text-sm text-slate-100 opacity-0 scale-95 shadow-xl shadow-black/30 transition-all duration-150 group-focus-within:opacity-100 group-focus-within:scale-100 group-focus-within:pointer-events-auto">
                        <a href="{{ route('member.profile') }}" class="block rounded-2xl px-3 py-2 text-sm text-slate-100 hover:bg-white/10">Settings</a>
                        <form action="{{ route('logout') }}" method="POST" class="mt-1">
                            @csrf
                            <button type="submit" class="w-full rounded-2xl px-3 py-2 text-left text-sm text-slate-100 hover:bg-white/10">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <section class="mt-10 rounded-[2.5rem] bg-white/10 p-8 shadow-2xl shadow-slate-950/10 ring-1 ring-white/10 backdrop-blur-xl">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.4em] text-cyan-100">Katalog Buku</p>
                    <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">Temukan buku favorit dan pinjam sekarang.</h1>
                    <p class="mt-3 text-sm text-slate-200">Pilih buku, lihat detail, dan ajukan peminjaman langsung dari halaman ini.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('welcome') }}" class="rounded-full border border-white/20 bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/20">Kembali</a>
                    <a href="{{ route('member.borrowings.index') }}" class="rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950 hover:bg-slate-100">Lihat Peminjaman</a>
                </div>
            </div>
        </section>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($books as $book)
                <div class="overflow-hidden rounded-3xl bg-white/10 shadow-xl shadow-black/20 ring-1 ring-white/10 transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="relative h-64 bg-slate-900">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover {{ $book->title }}" class="h-full w-full object-cover" />
                        @else
                            <div class="flex h-full items-center justify-center bg-slate-800 text-slate-300">Belum ada sampul</div>
                        @endif
                    </div>
                    <div class="space-y-4 p-5">
                        <div>
                            <h2 class="text-xl font-semibold text-white">{{ $book->title }}</h2>
                            <p class="mt-1 text-sm text-slate-300">{{ optional($book->author)->name ?? $book->author ?? 'Pengarang tidak tersedia' }}</p>
                        </div>
                        <div class="grid gap-2 text-sm text-slate-300">
                            <div><span class="font-semibold text-white">Penerbit:</span> {{ optional($book->publisher)->name ?? $book->publisher ?? '-' }}</div>
                            <div><span class="font-semibold text-white">Kategori:</span> {{ optional($book->category)->name ?? $book->category ?? '-' }}</div>
                            <div><span class="font-semibold text-white">Stok:</span> {{ $book->stock }}</div>
                        </div>
                        <p class="text-sm leading-6 text-slate-300">{{ Str::limit($book->description, 110, '...') }}</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('member.books.show', $book) }}" class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/20">Lihat</a>
                            <form action="{{ route('member.books.borrow', $book) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-950 shadow-lg shadow-black/20 hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50" {{ $book->stock < 1 ? 'disabled' : '' }}>
                                    Ajukan Pinjam
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-3xl border border-white/10 bg-slate-900/80 p-8 text-center text-slate-300">
                    <p class="text-lg font-semibold">Belum ada buku tersedia.</p>
                    <p class="mt-2 text-sm text-slate-400">Silakan hubungi petugas untuk informasi lebih lanjut.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
