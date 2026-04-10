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
                <a href="{{ route('member.books.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-900 rounded-full bg-white/90 hover:bg-white">Jelajahi Buku</a>
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

        <main class="grid gap-12 mt-16 pb-40 lg:grid-cols-[1.2fr_0.8fr] lg:items-start">
            <section class="space-y-6">
                <div class="max-w-2xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.4em] text-cyan-100">Selamat datang di PerpustakaanKu</p>
                    <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">Temukan buku favoritmu dan pinjam dalam beberapa klik.</h1>
                    <p class="mt-6 text-base leading-8 text-cyan-100">Nikmati koleksi buku lengkap, riwayat peminjaman yang mudah dipantau, dan pengalaman belajar yang nyaman. Semua dalam satu aplikasi perpustakaan digital.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-3xl bg-white/10 p-5 text-slate-100 shadow-lg shadow-slate-900/20 backdrop-blur-xl">
                        <h2 class="text-lg font-semibold">Katalog Buku</h2>
                        <p class="mt-2 text-sm text-slate-200">Cari buku berdasarkan kategori, pengarang, dan judul.</p>
                    </div>
                    <div class="rounded-3xl bg-white/10 p-5 text-slate-100 shadow-lg shadow-slate-900/20 backdrop-blur-xl">
                        <h2 class="text-lg font-semibold">Peminjaman Cepat</h2>
                        <p class="mt-2 text-sm text-slate-200">Ajukan pinjaman langsung dari dashboard anggota.</p>
                    </div>
                    <div class="rounded-3xl bg-white/10 p-5 text-slate-100 shadow-lg shadow-slate-900/20 backdrop-blur-xl">
                        <h2 class="text-lg font-semibold">Profil Anggota</h2>
                        <p class="mt-2 text-sm text-slate-200">Kelola data akun, password, dan foto profil.</p>
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('member.books.index') }}" class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900 shadow-lg shadow-slate-900/20 hover:bg-slate-100">Lihat Koleksi Buku</a>
                    <a href="{{ route('member.borrowings.index') }}" class="inline-flex items-center justify-center rounded-full border border-white/60 bg-white/10 px-6 py-3 text-sm font-semibold text-white hover:bg-white/20">Status Peminjaman</a>
                </div>
            </section>

            <section class="rounded-[2rem] bg-white/10 p-8 shadow-2xl shadow-slate-950/10 ring-1 ring-white/10 backdrop-blur-xl">
                <div class="space-y-5">
                    <div class="inline-flex items-center gap-3 rounded-full bg-slate-900/15 px-4 py-2 text-sm text-white">
                        <i class="bi bi-journal-bookmark-fill"></i>
                        <span>Halo, {{ auth()->user()->name }}</span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-semibold text-white">Ringkasan Cepat</h2>
                        <p class="mt-2 text-sm text-slate-200">Anda login sebagai anggota. Gunakan tombol di atas untuk mulai meminjam dan cek koleksi terbaru.</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-3xl bg-white/10 p-4">
                            <p class="text-sm text-slate-200">Buku Tersedia</p>
                            <p class="mt-3 text-3xl font-bold text-white">0</p>
                        </div>
                        <div class="rounded-3xl bg-white/10 p-4">
                            <p class="text-sm text-slate-200">Peminjaman Aktif</p>
                            <p class="mt-3 text-3xl font-bold text-white">0</p>
                        </div>
                    </div>
                    <div class="mt-4 rounded-3xl bg-white/5 p-4 text-sm leading-6 text-slate-200">Kunjungi halaman buku untuk melihat koleksi lengkap dan pilih judul yang ingin Anda pinjam. Jika ingin memperbarui profil atau password, buka halaman profil Anda.</div>
                </div>
            </section>
        </main>
    </div>
    <section class="bg-slate-800 py-12">
        <div class="mx-auto max-w-6xl px-5">

            <div class="grid gap-10 lg:grid-cols-2">

                <!-- Kiri -->
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-white">Kontak Perpustakaan</h2>
                    <p class="text-sm text-slate-300">
                        Silakan hubungi kami untuk informasi buku, pendaftaran anggota, dan status peminjaman.
                    </p>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-xs text-slate-400">Alamat</p>
                            <p class="mt-1 text-sm text-white">Jl. Contoh No. 123</p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">Telepon</p>
                            <p class="mt-1 text-sm text-white">(021) 1234 5678</p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">Email</p>
                            <p class="mt-1 text-sm text-white">info@perpustakaanku.id</p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">Jam</p>
                            <p class="mt-1 text-sm text-white">08:00 - 17:00</p>
                        </div>
                    </div>
                </div>

                <!-- Kanan -->
                <div class="overflow-hidden rounded-xl">
                    <div class="h-[260px] w-full">
                        <div id="map" class="h-full w-full"></div>
                    </div>
                </div>

            </div>

        </div>
    </section>
</div>
<script>
    var map = L.map('map').setView([-6.914744, 107.609810], 16); // Bandung - SMK MVP ARS

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker([-6.914744, 107.609810])
        .addTo(map)
        .bindPopup("SMK MVP ARS Internasional Bandung")
        .openPopup();
</script>
@endsection