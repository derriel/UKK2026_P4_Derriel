@extends('layouts.fullscreen-layout')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-sky-600 via-cyan-500 to-indigo-600 text-slate-100">
    <div class="px-4 py-5 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <header class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <a href="{{ route('welcome') }}" class="text-2xl font-bold tracking-tight text-white">PerpustakaanKu</a>
                <p class="mt-2 text-sm text-slate-200">Lihat status peminjaman buku Anda dengan jelas dan sederhana.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('member.books.index') }}" class="inline-flex items-center justify-center rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-900 shadow-lg shadow-black/20 hover:bg-slate-100">Katalog Buku</a>
                <a href="{{ route('welcome') }}" class="inline-flex items-center justify-center rounded-full border border-white/25 bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/20">Kembali</a>
            </div>
        </header>

        <section class="mt-10 grid gap-8 lg:grid-cols-[1.3fr_0.7fr]">
            <div class="rounded-[2rem] bg-white/10 p-8 shadow-2xl shadow-black/20 ring-1 ring-white/10 backdrop-blur-xl">
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.35em] text-cyan-100">Riwayat Peminjaman</p>
                        <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-white">Monitor semua status pinjamanmu.</h1>
                        <p class="mt-4 text-sm leading-7 text-slate-200">Lihat seberapa lama buku dipinjam, tanggal kembali, dan status dengan baris warna yang jelas.</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-3xl bg-slate-900/80 p-5">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Jumlah Peminjaman</p>
                            <p class="mt-3 text-3xl font-bold text-white">{{ $borrowings->count() }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-900/80 p-5">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Dipinjam</p>
                            <p class="mt-3 text-3xl font-bold text-white">{{ $borrowings->where('status','borrowed')->count() }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-900/80 p-5">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Dikembalikan</p>
                            <p class="mt-3 text-3xl font-bold text-white">{{ $borrowings->where('status','returned')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="rounded-[2rem] bg-white/10 p-8 shadow-2xl shadow-black/20 ring-1 ring-white/10 backdrop-blur-xl">
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-3 rounded-full bg-white/10 px-4 py-2 text-sm text-white">
                        <i class="bi bi-clock-history"></i>
                        <span>Detail Peminjaman</span>
                    </div>
                    <p class="text-sm text-slate-200">Jika ingin melihat detail buku atau status pinjaman, gunakan halaman katalog untuk memilih buku baru.</p>
                    <a href="{{ route('member.books.index') }}" class="inline-flex items-center justify-center rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-100">Kembali ke Katalog</a>
                </div>
            </aside>
        </section>

        <section class="mt-8">
            <div class="overflow-hidden rounded-[2rem] bg-white/10 shadow-2xl shadow-black/20 ring-1 ring-white/10">
                <table class="min-w-full divide-y divide-white/10 text-left text-sm text-slate-200">
                    <thead class="bg-slate-900/95 text-slate-300">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Judul Buku</th>
                            <th class="px-6 py-4">Tanggal Pinjam</th>
                            <th class="px-6 py-4">Tanggal Kembali</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 bg-slate-950/90">
                        @forelse($borrowings as $index => $borrowing)
                        <tr class="transition hover:bg-white/5">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-semibold text-white">{{ $borrowing->book->title ?? '-' }}</td>
                            <td class="px-6 py-4">{{ optional($borrowing->borrow_date)->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-6 py-4">{{ optional($borrowing->due_date)->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($borrowing->status === 'borrowed')
                                    <span class="inline-flex rounded-full bg-amber-500/15 px-3 py-1 text-amber-200">Dipinjam</span>
                                @elseif($borrowing->status === 'requested')
                                    <span class="inline-flex rounded-full bg-blue-500/15 px-3 py-1 text-blue-200">Menunggu Persetujuan</span>
                                @elseif($borrowing->status === 'return_requested')
                                    <span class="inline-flex rounded-full bg-violet-500/15 px-3 py-1 text-violet-200">Pengembalian Diajukan</span>
                                @elseif($borrowing->status === 'returned')
                                    <span class="inline-flex rounded-full bg-emerald-500/15 px-3 py-1 text-emerald-200">Dikembalikan</span>
                                @elseif($borrowing->status === 'overdue')
                                    <span class="inline-flex rounded-full bg-red-500/15 px-3 py-1 text-red-200">Terlambat</span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-700 px-3 py-1 text-slate-300">{{ ucfirst($borrowing->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($borrowing->status === 'borrowed')
                                    <form action="{{ route('member.borrowings.return', $borrowing) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Ajukan Pengembalian</button>
                                    </form>
                                @elseif($borrowing->status === 'requested')
                                    <span class="inline-flex rounded-full bg-blue-500/10 px-3 py-1 text-blue-100">Menunggu persetujuan</span>
                                @elseif($borrowing->status === 'return_requested')
                                    <span class="inline-flex rounded-full bg-violet-500/10 px-3 py-1 text-violet-100">Pengembalian diajukan</span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-700 px-3 py-1 text-slate-300">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-300">Belum ada riwayat peminjaman. Ayo pinjam buku sekarang!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
@endsection