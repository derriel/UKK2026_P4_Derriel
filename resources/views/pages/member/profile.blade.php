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
    <main class="px-4 mx-auto max-w-2xl sm:px-6 lg:px-8 py-8">
        <a href="{{ route('welcome') }}" class="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-orange-600 mb-4">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Profil</h1>
            <p class="text-gray-600 mt-1">Perbarui informasi akunmu</p>

            @if(session('success'))
            <div class="mt-4 p-3 bg-green-50 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                @csrf
                @method('PUT')

                <div class="flex justify-center">
                    <div class="h-24 w-24 rounded-full bg-gray-200 overflow-hidden flex items-center justify-center">
                        @if($user->photo)
                        <img src="{{ Storage::url($user->photo) }}" alt="Profile" class="h-full w-full object-cover">
                        @else
                        <i class="fa-regular fa-circle-user text-4xl text-gray-400"></i>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                    <input type="file" name="photo" accept="image/*" class="w-full text-sm text-gray-500">
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah foto</p>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-lg bg-orange-600 text-white font-medium hover:bg-orange-700">Simpan Perubahan</button>
            </form>
        </div>
    </main>

    <footer class="bg-gray-900 py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} PerpustakaanKu - SMK MVP ARS Internasional</p>
        </div>
    </footer>
</div>
@endsection