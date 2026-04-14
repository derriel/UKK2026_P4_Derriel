@extends('layouts.fullscreen-layout')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-sky-600 via-cyan-500 to-indigo-600 text-slate-900 dark:text-slate-100">
    <div class="px-4 py-5 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <header class="flex items-center justify-between gap-4">
            <div>
                <a href="{{ route('welcome') }}" class="text-xl font-bold tracking-tight text-white">PerpustakaanKu</a>
                <p class="mt-1 text-sm text-slate-200">Detail buku anggota.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('member.books.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-900 rounded-full bg-white/90 hover:bg-white">Kembali ke Katalog</a>
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

        <main class="mt-10 grid gap-12 lg:grid-cols-[0.75fr_0.45fr] lg:items-start">
            <section class="rounded-3xl bg-white/10 p-8 shadow-2xl shadow-slate-950/10 ring-1 ring-white/10 backdrop-blur-xl">
                <div class="space-y-6">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.4em] text-cyan-100">Detail Buku</p>
                        <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">{{ $book->title }}</h1>
                        <p class="mt-3 text-sm text-slate-200">{{ optional($book->author)->name ?? $book->author ?? 'Pengarang tidak diketahui' }}</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 text-sm text-slate-300">
                        <div class="rounded-3xl bg-slate-900/80 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Penerbit</p>
                            <p class="mt-2 text-base text-white">{{ optional($book->publisher)->name ?? $book->publisher ?? '-' }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-900/80 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Kategori</p>
                            <p class="mt-2 text-base text-white">{{ optional($book->category)->name ?? $book->category ?? '-' }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-900/80 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">ISBN</p>
                            <p class="mt-2 text-base text-white">{{ $book->isbn ?? '-' }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-900/80 p-5">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Stok</p>
                            <p class="mt-2 text-base text-white">{{ $book->stock }}</p>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-slate-900/80 p-6 text-slate-200">
                        <h2 class="text-lg font-semibold text-white">Ringkasan</h2>
                        <p class="mt-4 leading-7">{{ $book->description ? $book->description : 'Deskripsi buku belum tersedia.' }}</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('member.books.index') }}" class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/20">Kembali ke Katalog</a>
                        <form action="{{ route('member.books.borrow', $book) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-black/20 hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-50" {{ $book->stock < 1 ? 'disabled' : '' }}>
                                Ajukan Pinjam
                            </button>
                        </form>
                    </div>
                    <p class="text-xs text-slate-400">Pengajuan ini akan dikirim ke petugas/admin untuk disetujui.</p>
                </div>
            </section>

            <aside class="rounded-[2rem] bg-white/10 p-8 shadow-2xl shadow-slate-950/10 ring-1 ring-white/10 backdrop-blur-2xl">
                <div class="overflow-hidden rounded-[1.75rem] bg-slate-900/80 backdrop-blur-xl">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover {{ $book->title }}" class="h-full w-full object-cover" />
                    @else
                        <div class="flex min-h-[420px] items-center justify-center bg-slate-800 p-10 text-center text-slate-300">
                            <span class="text-lg font-semibold">Tidak ada sampul tersedia</span>
                        </div>
                    @endif
                </div>
            </aside>
        </main>
    </div>
</div>
@endsection
