@extends('layouts.fullscreen-layout')

@section('content')
    <div class="min-h-screen bg-slate-950 text-white">
        <div class="px-4 py-5 mx-auto max-w-6xl sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <a href="{{ route('welcome') }}" class="text-xl font-bold tracking-tight text-white">PerpustakaanKu</a>
                    <p class="mt-1 text-sm text-slate-300">Lihat ringkasan peminjaman Anda.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('member.books.index') }}" class="rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/20">Katalog Buku</a>
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
                        <h1 class="text-3xl font-bold text-white">Status Peminjaman</h1>
                        <p class="mt-2 text-sm text-slate-400">Monitor semua peminjaman anggota Anda.</p>
                    </div>
                    <a href="{{ route('welcome') }}" class="rounded-full border border-white/20 px-5 py-3 text-sm font-semibold text-white hover:bg-white/10">Kembali</a>
                </div>

                <div class="mt-8 overflow-x-auto rounded-3xl border border-white/10 bg-slate-950/90">
                    <table class="min-w-full divide-y divide-white/10 text-left text-sm text-slate-200">
                        <thead class="bg-slate-900/95 text-slate-300">
                            <tr>
                                <th class="px-6 py-4">No</th>
                                <th class="px-6 py-4">Judul Buku</th>
                                <th class="px-6 py-4">Tanggal Pinjam</th>
                                <th class="px-6 py-4">Tanggal Kembali</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($borrowings as $index => $borrowing)
                                <tr class="hover:bg-slate-900/80">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">{{ $borrowing->book->title ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ optional($borrowing->borrow_date)->format('Y-m-d') ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ optional($borrowing->due_date)->format('Y-m-d') ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($borrowing->status === 'borrowed')
                                            <span class="inline-flex rounded-full bg-amber-500/15 px-3 py-1 text-amber-200">Dipinjam</span>
                                        @elseif($borrowing->status === 'returned')
                                            <span class="inline-flex rounded-full bg-emerald-500/15 px-3 py-1 text-emerald-200">Dikembalikan</span>
                                        @elseif($borrowing->status === 'overdue')
                                            <span class="inline-flex rounded-full bg-red-500/15 px-3 py-1 text-red-200">Terlambat</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-700 px-3 py-1 text-slate-300">{{ ucfirst($borrowing->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-400">Tidak ada peminjaman aktif. Ayo pinjam buku sekarang!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
