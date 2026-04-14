@extends('layouts.fullscreen-layout')

@section('content')
    <div class="min-h-screen bg-slate-950 text-white">
        <div class="px-4 py-5 mx-auto max-w-6xl sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <a href="{{ route('welcome') }}" class="text-xl font-bold tracking-tight text-white">PerpustakaanKu</a>
                    <p class="mt-1 text-sm text-slate-300">Kelola profil dan pengaturan akun siswa Anda.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('member.books.index') }}" class="rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/20">Katalog Buku</a>
                    <div class="relative group" tabindex="0">
                        <button type="button" class="grid h-11 w-11 place-items-center rounded-full bg-white/90 text-slate-900 shadow-lg shadow-slate-950/10 transition hover:bg-white">
                            <i class="bi bi-person-fill text-lg"></i>
                        </button>
                        <div class="pointer-events-none absolute right-0 z-20 mt-2 w-48 rounded-3xl bg-slate-950/95 p-2 text-sm text-slate-100 opacity-0 scale-95 shadow-xl shadow-black/30 transition-all duration-150 group-focus-within:opacity-100 group-focus-within:scale-100 group-focus-within:pointer-events-auto">
                            <a href="{{ route('member.profile') }}" class="block rounded-2xl px-3 py-2 text-slate-100 hover:bg-white/10">Profil</a>
                            <form action="{{ route('logout') }}" method="POST" class="mt-1">
                                @csrf
                                <button type="submit" class="w-full rounded-2xl px-3 py-2 text-left text-slate-100 hover:bg-white/10">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 rounded-[2rem] bg-white/10 p-8 shadow-2xl shadow-slate-950/20 ring-1 ring-white/10 backdrop-blur-xl">
                <div class="grid gap-8 lg:grid-cols-[0.9fr_0.7fr] lg:items-start">
                    <div class="space-y-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-white">Profil Siswa</h1>
                                <p class="mt-2 text-sm text-slate-300">Perbarui data akun, password, dan foto profil kapan saja.</p>
                            </div>
                            <a href="{{ route('member.borrowings.index') }}" class="rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-black/20 hover:bg-slate-100">Lihat Peminjaman</a>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="rounded-3xl bg-slate-900/80 p-6">
                                <h2 class="text-lg font-semibold text-white">Informasi Akun</h2>
                                <div class="mt-4 space-y-3 text-sm text-slate-300">
                                    <div>
                                        <span class="block text-slate-400">Nama</span>
                                        <p class="mt-1 text-white">{{ auth()->user()->name }}</p>
                                    </div>
                                    <div>
                                        <span class="block text-slate-400">Email</span>
                                        <p class="mt-1 text-white">{{ auth()->user()->email }}</p>
                                    </div>
                                    <div>
                                        <span class="block text-slate-400">Role</span>
                                        <p class="mt-1 text-white">{{ optional(auth()->user()->role)->name ?? 'Siswa' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-3xl bg-slate-900/80 p-6">
                                <h2 class="text-lg font-semibold text-white">Foto Profil</h2>
                                <div class="mt-6 flex items-center gap-4">
                                    <div class="flex h-24 w-24 items-center justify-center rounded-full bg-white/10 text-3xl text-slate-300">
                                        @if(optional($member)->photo)
                                            <img src="{{ asset('storage/' . $member->photo) }}" alt="Foto profil" class="h-full w-full rounded-full object-cover" />
                                        @else
                                            <i class="bi bi-person-fill"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-300">Unggah foto profil untuk tampilan akun yang lebih personal.</p>
                                        <p class="mt-2 text-sm text-slate-400">Fitur update profil akan diaktifkan di versi berikutnya.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-3xl bg-slate-900/80 p-6 text-slate-300">
                            <p class="text-sm">Halaman profile member, gunakan tombol Settings di pojok atas untuk akses cepat. Tambahkan fitur update password atau foto profil di kemudian hari.</p>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-slate-900/80 p-6">
                        <h2 class="text-lg font-semibold text-white">Ringkasan Singkat</h2>
                        <p class="mt-3 text-sm text-slate-300">Halaman profile ini khusus untuk anggota, tanpa sidebar atau tampilan dashboard admin.</p>
                        <div class="mt-6 grid gap-4">
                            <div class="rounded-3xl bg-slate-950/70 p-4">
                                <p class="text-sm text-slate-400">Akses cepat</p>
                                <p class="mt-2 text-white">Kelola akun dan cek riwayat peminjaman Anda.</p>
                            </div>
                            <div class="rounded-3xl bg-slate-950/70 p-4">
                                <p class="text-sm text-slate-400">Dukungan</p>
                                <p class="mt-2 text-white">Hubungi admin jika butuh bantuan meminjam buku.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
