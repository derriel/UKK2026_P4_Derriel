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
    <main class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 py-8">
        <a href="{{ route('welcome') }}" class="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-orange-600 mb-4">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <div class="grid gap-8 lg:grid-cols-[300px_1fr]">
            <!-- Book Cover -->
            <div>
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden sticky top-20">
                    <div class="aspect-[3/4] bg-gray-100">
                        @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
                        @else
                        <div class="flex h-full items-center justify-center bg-gray-200">
                            <i class="bi bi-book text-5xl text-gray-400"></i>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Book Details -->
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $book->title }}</h1>
                <p class="mt-2 text-lg text-gray-600">{{ optional($book->author)->name ?? 'Pengarang tidak diketahui' }}</p>

                <!-- Book Info -->
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Penerbit</p>
                        <p class="font-medium text-gray-900">{{ optional($book->publisher)->name ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Kategori</p>
                        <p class="font-medium text-gray-900">{{ optional($book->category)->name ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">ISBN</p>
                        <p class="font-medium text-gray-900">{{ $book->isbn ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Rak</p>
                        <p class="font-medium text-gray-900">{{ optional($book->rack)->name ?? '-' }}</p>
                        @if($book->rack && $book->rack->description)
                        <p class="text-xs text-gray-500 mt-1">{{ $book->rack->description }}</p>
                        @endif
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-500">Stok</p>
                        <p class="font-medium {{ $book->stock > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $book->stock }}</p>
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h2>
                    <p class="text-gray-600">{{ $book->description ?? 'Deskripsi buku belum tersedia.' }}</p>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex flex-wrap gap-4">
                    @if($book->stock > 0)
                    <button type="button" onclick="document.getElementById('borrowModal').classList.remove('hidden'); updateDays(7);" class="px-6 py-3 font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700">Ajukan Pinjam</button>
                    @else
                    <button type="button" disabled class="px-6 py-3 font-medium text-gray-500 bg-gray-200 rounded-lg cursor-not-allowed">Stok Habis</button>
                    @endif
                    <a href="{{ route('member.books.index') }}" class="px-6 py-3 font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Lihat Buku Lain</a>
                </div>
                <p class="mt-3 text-sm text-gray-500">Pengajuan akan diproses oleh petugas</p>

                <!-- Modal Peminjaman -->
                <div id="borrowModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                    <div class="flex min-h-screen items-center justify-center px-4">
                        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="document.getElementById('borrowModal').classList.add('hidden')"></div>
                        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6 z-10">
                            <button type="button" onclick="document.getElementById('borrowModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Ajukan Peminjaman Buku</h3>
                            <p class="text-gray-600 mb-4"> Anda akan mengajukan peminjaman untuk buku:</p>
                            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                <p class="font-medium text-gray-900">{{ $book->title }}</p>
                                <p class="text-sm text-gray-500">oleh {{ optional($book->author)->name ?? '-' }}</p>
                            </div>
                            
                            <form action="{{ route('member.books.borrow', $book) }}" method="POST" id="borrowForm">
                                @csrf
                                <input type="hidden" name="days" id="selectedDays" value="7">
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Lama Peminjaman (Hari)</label>
                                    <input type="range" min="1" max="14" value="7" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" id="daysRange" oninput="updateDays(this.value)">
                                    <div class="flex justify-between text-sm text-gray-500 mt-1">
                                        <span>1 hari</span>
                                        <span class="font-medium text-orange-600 text-lg" id="daysDisplay">7 hari</span>
                                        <span>14 hari</span>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Jatuh Tempo</label>
                                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                                        <p class="text-lg font-bold text-orange-700" id="dueDateDisplay">-</p>
                                        <p class="text-xs text-orange-600">Buku harus dikembalikan sebelum/tanggal ini</p>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Denda Keterlambatan</label>
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                        <p class="text-lg font-bold text-red-700">Rp {{ number_format($book->fine_per_day ?? 5000, 0, ',', '.') }} / hari</p>
                                        <p class="text-xs text-red-600">Denda akan dikenakan jika buku dikembalikan setelah jatuh tempo</p>
                                    </div>
                                </div>
                                
                                <div class="flex gap-3">
                                    <button type="button" onclick="closeBorrowModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Batal</button>
                                    <button type="submit" class="flex-1 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">Kirim Pengajuan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    function updateDays(days) {
                        document.getElementById('selectedDays').value = days;
                        document.getElementById('daysDisplay').textContent = days + ' hari';
                        
                        const today = new Date();
                        const dueDate = new Date(today);
                        dueDate.setDate(today.getDate() + parseInt(days));
                        
                        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        document.getElementById('dueDateDisplay').textContent = dueDate.toLocaleDateString('id-ID', options);
                    }
                </script>
            </div>
        </div>
    </main>

    <footer class="bg-gray-900 py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} PerpustakaanKu - SMK MVP ARS Internasional</p>
        </div>
    </footer>
</div>
@endsection