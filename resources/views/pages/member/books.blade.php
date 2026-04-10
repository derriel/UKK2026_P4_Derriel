@extends('layouts.fullscreen-layout')

@section('content')
    <div class="min-h-screen bg-slate-950 text-white">
        <div class="px-4 py-5 mx-auto max-w-6xl sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <a href="{{ route('welcome') }}" class="text-xl font-bold tracking-tight text-white">PerpustakaanKu</a>
                    <p class="mt-1 text-sm text-slate-300">Katalog buku khusus anggota.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('welcome') }}" class="rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/20">Beranda Anggota</a>
                    <div class="relative group" tabindex="0">
                        <button type="button" class="grid h-11 w-11 place-items-center rounded-full bg-white/90 text-slate-900 shadow-lg shadow-slate-950/10 transition hover:bg-white">
                            <i class="bi bi-person-fill text-lg"></i>
                        </button>
                        <div class="pointer-events-none absolute right-0 z-20 mt-2 w-48 rounded-3xl bg-slate-950/95 p-2 text-sm text-slate-100 opacity-0 scale-95 shadow-xl shadow-black/30 transition-all duration-150 group-focus-within:opacity-100 group-focus-within:scale-100 group-focus-within:pointer-events-auto">
                            <a href="{{ route('member.profile') }}" class="block rounded-2xl px-3 py-2 text-slate-100 hover:bg-white/10">Settings</a>
                            <form action="{{ route('logout') }}" method="POST" class="mt-1">
                                @csrf
                                <button type="submit" class="w-full rounded-2xl px-3 py-2 text-left text-slate-100 hover:bg-white/10">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 rounded-3xl bg-slate-900/80 p-8 shadow-2xl shadow-black/20 ring-1 ring-white/10">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white">Katalog Buku</h1>
                        <p class="mt-2 text-sm text-slate-400">Telusuri koleksi buku terbaru dan pilih judul yang ingin dipinjam.</p>
                    </div>
                    <div class="inline-flex items-center gap-3">
                        <a href="{{ route('member.borrowings.index') }}" class="rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-black/20 hover:bg-slate-100">Lihat Status Peminjaman</a>
                        <a href="{{ route('welcome') }}" class="rounded-full border border-white/20 px-5 py-3 text-sm font-semibold text-white hover:bg-white/10">Kembali</a>
                    </div>
                </div>

                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse($books as $book)
                        <div class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/80 shadow-xl shadow-black/20">
                            <div class="relative h-64 bg-slate-800">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover {{ $book->title }}" class="h-full w-full object-cover" />
                                @else
                                    <div class="flex h-full items-center justify-center bg-slate-700 text-slate-300">No Cover</div>
                                @endif
                            </div>
                            <div class="space-y-3 p-5">
                                <div>
                                    <h2 class="text-xl font-semibold text-white">{{ $book->title }}</h2>
                                    <p class="text-sm text-slate-400">{{ $book->author }}</p>
                                </div>
                                <div class="grid gap-2 text-sm text-slate-300">
                                    <div><span class="font-semibold">Penerbit:</span> {{ $book->publisher ?? '-' }}</div>
                                    <div><span class="font-semibold">Kategori:</span> {{ $book->category ?? '-' }}</div>
                                    <div><span class="font-semibold">Stok:</span> {{ $book->stock }}</div>
                                </div>
                                <p class="text-sm leading-6 text-slate-300">{{ Str::limit($book->description, 120, '...') }}</p>
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
    </div>
@endsection
