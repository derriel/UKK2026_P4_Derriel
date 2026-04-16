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
                        @php
                            $isOverdue = $borrowing->status === 'borrowed' && \Carbon\Carbon::parse($borrowing->due_date)->lessThan(now());
                        @endphp
                        <h3 class="font-semibold text-gray-900">{{ $borrowing->book->title ?? 'Buku tidak tersedia' }}</h3>
                        <p class="text-sm text-gray-500">{{ optional($borrowing->book)->author->name ?? '-' }}</p>
                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                            <span>Pinjam: {{ optional($borrowing->borrow_date)->format('d M') ?? '-' }}</span>
                            <span>Kembali: {{ optional($borrowing->due_date)->format('d M Y') ?? '-' }}</span>
                            @if($isOverdue)
                            <span class="text-red-600 font-medium">
                                (Terlambat {{ (int)now()->diffInDays($borrowing->due_date) }} hari)
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        @if($isOverdue)
                        <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Terlambat</span>
                        @elseif($borrowing->status === 'borrowed')
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
                            @php
                                $dueDate = \Carbon\Carbon::parse($borrowing->due_date);
                                $isOverdueForFine = now()->greaterThan($dueDate);
                                
                                // Check if fine is already paid
                                $isFinePaid = $borrowing->fine_status === 'paid';
                                
                                // Calculate fine if overdue
                                if ($isOverdueForFine && $borrowing->fine <= 0) {
                                    $daysLate = (int)now()->diffInDays($dueDate);
                                    $finePerDay = optional($borrowing->book)->fine_per_day ?? 5000;
                                    $calculatedFine = $daysLate * $finePerDay;
                                } else {
                                    $calculatedFine = $borrowing->fine;
                                }
                                
                                // Show "Bayar Denda" if: overdue (with calculated fine) OR unpaid fine
                                $showPayFine = !$isFinePaid && ($isOverdueForFine || $calculatedFine > 0);
                            @endphp
                            @if($showPayFine)
                            <div class="flex flex-col items-end gap-2">
                                @if($isOverdueForFine)
                                <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                    <i class="bi bi-exclamation-triangle mr-1"></i>Buku Terlambat! (Harus bayarkan denda)
                                </span>
                                @else
                                <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                    <i class="bi bi-exclamation-triangle mr-1"></i>Denda Belum Lunas
                                </span>
                                @endif
                                <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700" onclick="showPaymentModal({{ $borrowing->id }}, {{ $calculatedFine }})">Bayar Denda</button>
                            </div>
                            @else
                            <form action="{{ route('member.borrowings.return', $borrowing) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700">Kembalikan</button>
                            </form>
                            @endif
                        @else
                        <a href="{{ route('member.books.show', $borrowing->book) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Detail</a>
                        @endif

                        @if(in_array($borrowing->status, ['returned', 'return_requested']) && $borrowing->fine_status === 'unpaid' && $borrowing->fine > 0)
                        <div class="flex flex-col items-end gap-2 mt-2">
                            <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                <i class="bi bi-exclamation-triangle mr-1"></i>Denda: Rp {{ number_format($borrowing->fine, 0, ',', '.') }}
                            </span>
                            <form action="{{ route('member.borrowings.payFine', $borrowing) }}" method="POST">
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

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="fixed inset-0 bg-black/50" onclick="closePaymentModal()"></div>
            <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6 z-10">
                <button type="button" onclick="closePaymentModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Pembayaran Denda</h3>
                <p class="text-gray-600 mb-4">Silakan pilih metode pembayaran:</p>
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <p class="text-sm text-gray-500">Total Denda</p>
                    <p class="text-2xl font-bold text-red-600" id="modalFineAmount">Rp 0</p>
                </div>
                <div class="space-y-3">
                    <button type="button" onclick="showQRIS()" class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        QRIS
                    </button>
                    <button type="button" onclick="showTransfer()" class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Transfer Bank
                    </button>
                </div>

                <!-- QRIS Section -->
                <div id="qrisSection" class="hidden mt-4">
                    <div class="bg-white border-2 border-blue-500 rounded-lg p-4 text-center">
                        <p class="text-sm text-gray-500 mb-2">Scan QR Code dengan HP Anda</p>
                        <img id="qrisImage" src="" alt="QRIS" class="w-48 h-48 mx-auto">
                        <p class="text-xs text-gray-400 mt-2">atau masukkan kode berikut:</p>
                        <p class="font-mono font-bold text-blue-600" id="qrisCode">-</p>
                    </div>
                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <p class="text-sm text-yellow-700">📱 Buka aplikasi GoPay/OVO/ShopeePay/Dana dan scan QR di atas</p>
                    </div>
                    <button type="button" onclick="confirmPayment()" class="w-full mt-4 px-4 py-3 bg-green-600 text-white rounded-lg font-medium">
                        Konfirmasi Sudah Bayar
                    </button>
                </div>

                <!-- Transfer Section -->
                <div id="transferSection" class="hidden mt-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Transfer ke:</p>
                        <div class="bg-white border rounded-lg p-3 mb-3">
                            <p class="text-lg font-bold text-gray-900">Bank BCA</p>
                            <p class="font-mono text-xl">123 456 7890</p>
                            <p class="text-sm text-gray-500">a.n. PerpustakaanKu</p>
                        </div>
                        <p class="text-xs text-gray-500">Jumlah transfer:</p>
                        <p class="text-xl font-bold text-red-600" id="transferAmount">Rp 0</p>
                    </div>
                    <button type="button" onclick="confirmPayment()" class="w-full mt-4 px-4 py-3 bg-green-600 text-white rounded-lg font-medium">
                        Konfirmasi Sudah Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentBorrowingId = null;
        let currentFineAmount = 0;
        
        function showPaymentModal(borrowingId, fineAmount) {
            currentBorrowingId = borrowingId;
            currentFineAmount = fineAmount;
            document.getElementById('modalFineAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(fineAmount);
            document.getElementById('transferAmount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(fineAmount);
            document.getElementById('paymentModal').classList.remove('hidden');
            
            // Reset sections
            document.getElementById('qrisSection').classList.add('hidden');
            document.getElementById('transferSection').classList.add('hidden');
        }
        
        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            currentBorrowingId = null;
            currentFineAmount = 0;
        }
        
        function showQRIS() {
            // Generate fake QRIS data
            const fineFormatted = currentFineAmount.toString().padStart(10, '0');
            const qrisCode = 'ID10PERPUSTAKAAN' + fineFormatted + 'QRIS';
            
            // Generate QR code using Google Charts API (no library needed)
            const qrUrl = 'https://quickchart.io/qr?size=200x200&text=' + encodeURIComponent(qrisCode);
            document.getElementById('qrisImage').src = qrUrl;
            document.getElementById('qrisCode').textContent = qrisCode;
            
            document.getElementById('qrisSection').classList.remove('hidden');
            document.getElementById('transferSection').classList.add('hidden');
        }
        
        function showTransfer() {
            document.getElementById('transferSection').classList.remove('hidden');
            document.getElementById('qrisSection').classList.add('hidden');
        }
        
        function confirmPayment() {
            if (!currentBorrowingId) return;
            
            const btn = event.target;
            btn.disabled = true;
            btn.textContent = 'Memproses...';
            
            fetch('/member/borrowings/' + currentBorrowingId + '/pay-fine', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Konfirmasi Sudah Bayar';
                
                if (data.success) {
                    console.log('Payment response:', data);
                    alert('Pembayaran denda berhasil! 🎉\n\nPembayaran telah kami terima.');
                    closePaymentModal();
                    // Replace current page to force fresh load
                    window.location.replace(window.location.href);
                } else {
                    console.log('Payment failed:', data);
                    alert(data.message || 'Pembayaran gagal');
                }
            })
            .catch(error => {
                btn.disabled = false;
                btn.textContent = 'Konfirmasi Sudah Bayar';
                alert('Terjadi kesalahan: ' + error.message);
            });
        }
    </script>
</div>
@endsection