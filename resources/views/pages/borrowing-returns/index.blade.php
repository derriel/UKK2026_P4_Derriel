@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Kelola Peminjaman & Pengembalian" />
    <div class="space-y-6" x-data="{
            openModal: false,
            showReturnModal: false,
            showDeleteModal: false,
            editingId: null,
            returnId: null,
            deleteId: null,
            createUrl: '{{ route('borrowing-returns.store') }}',
            updateUrlBase: '{{ url('/borrowing-returns') }}/',
            deleteUrlBase: '{{ url('/borrowing-returns') }}/',
            returnUrlBase: '{{ url('/borrowing-returns') }}/',
            formData: {
                user_id: '',
                book_id: '',
                role_id: '',
                borrow_date: '',
                due_date: '',
                return_date: '',
                status: 'borrowed',
            },

            openCreateModal() {
                this.editingId = null;
                this.formData = {
                    user_id: '',
                    book_id: '',
                    role_id: '',
                    borrow_date: '',
                    due_date: '',
                    return_date: '',
                    status: 'borrowed',
                };
                this.openModal = true;
            },

            editBorrowing(borrowing) {
                this.editingId = borrowing.id;
                this.formData = {
                    user_id: borrowing.user_id,
                    book_id: borrowing.book_id,
                    role_id: borrowing.role_id,
                    borrow_date: borrowing.borrow_date,
                    due_date: borrowing.due_date,
                    return_date: borrowing.return_date,
                    status: borrowing.status,
                };
                this.openModal = true;
            },

            openReturnModal(id) {
                this.returnId = id;
                this.showReturnModal = true;
            },

            cancelReturn() {
                this.showReturnModal = false;
                this.returnId = null;
            },

            openDeleteModal(id) {
                this.deleteId = id;
                this.showDeleteModal = true;
            },

            cancelDelete() {
                this.showDeleteModal = false;
                this.deleteId = null;
            },
        }">
        <x-common.component-card title="Daftar Peminjaman & Pengembalian">
            <div class="flex justify-end mb-4">
                <button @click="openCreateModal()" class="btn btn-primary">
                    + Tambah Peminjaman
                </button>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/30 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">No</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Anggota</th>
                            <th class="px-4 py-3 text-left font-semibold">Judul Buku</th>
                            <th class="px-4 py-3 text-left font-semibold">Tgl. Peminjaman</th>
                            <th class="px-4 py-3 text-left font-semibold">Tgl. Kembali</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $index => $peminjaman)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">{{ $peminjaman->user->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $peminjaman->book->title ?? '-' }}</td>
                                <td class="px-4 py-3">{{ optional($peminjaman->borrow_date)->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-4 py-3">{{ optional($peminjaman->return_date)->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if($peminjaman->status === 'borrowed')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 rounded text-xs font-semibold">
                                            Dipinjam
                                        </span>
                                    @elseif($peminjaman->status === 'returned')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 rounded text-xs font-semibold">
                                            Dikembalikan
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 rounded text-xs font-semibold">
                                            Terlambat
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center gap-2 flex-wrap">
                                        @if($peminjaman->status === 'borrowed')
                                            <button @click="openReturnModal({{ $peminjaman->id }})" class="btn btn-sm btn-success inline-flex items-center gap-2">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8 1V15M1 8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                Kembalikan
                                            </button>
                                        @endif
                                        <button @click="editBorrowing(@js(['id' => $peminjaman->id, 'user_id' => $peminjaman->user_id, 'book_id' => $peminjaman->book_id, 'role_id' => $peminjaman->role_id, 'borrow_date' => $peminjaman->borrow_date?->format('Y-m-d'), 'due_date' => $peminjaman->due_date?->format('Y-m-d'), 'return_date' => $peminjaman->return_date?->format('Y-m-d'), 'status' => $peminjaman->status]))" class="btn btn-sm btn-outline-primary inline-flex items-center gap-2">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.5 2L14 4.5L5 13.5H2.5V11L11.5 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            Edit
                                        </button>
                                        <button @click="openDeleteModal({{ $peminjaman->id }})" class="btn btn-sm btn-outline-danger inline-flex items-center gap-2">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2 4H14M6.5 7V12M9.5 7V12M3 4L4 14C4 14.5304 4.21071 15.0391 4.58579 15.4142C4.96086 15.7893 5.46957 16 6 16H10C10.5304 16 11.0391 15.7893 11.4142 15.4142C11.7893 15.0391 12 14.5304 12 14L13 4M5.5 4V2.5C5.5 2.23478 5.60536 1.98043 5.79289 1.79289C5.98043 1.60536 6.23478 1.5 6.5 1.5H9.5C9.76522 1.5 10.0196 1.60536 10.2071 1.79289C10.3946 1.98043 10.5 2.23478 10.5 2.5V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada data peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-common.component-card>

        <!-- Modal Create/Edit -->
        <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="openModal = false">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96 max-h-96 overflow-y-auto">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                    <span x-show="!editingId">Tambah Peminjaman Baru</span>
                    <span x-show="editingId">Edit Peminjaman</span>
                </h2>
                
                <form x-ref="borrowingForm" method="POST" :action="editingId ? updateUrlBase + editingId : createUrl">
                    @csrf
                    <input type="hidden" name="_method" :value="editingId ? 'PUT' : 'POST'">

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Anggota</label>
                            <select x-model="formData.user_id" name="user_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                                <option value="">-- Pilih Anggota --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Buku</label>
                            <select x-model="formData.book_id" name="book_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                                <option value="">-- Pilih Buku --</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                            <select x-model="formData.role_id" name="role_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Peminjaman</label>
                            <input x-model="formData.borrow_date" name="borrow_date" type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Jatuh Tempo</label>
                            <input x-model="formData.due_date" name="due_date" type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Kembali (Direncanakan)</label>
                            <input x-model="formData.return_date" name="return_date" type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select x-model="formData.status" name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                                <option value="borrowed">Dipinjam</option>
                                <option value="returned">Dikembalikan</option>
                                <option value="overdue">Terlambat</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 mt-6">
                        <button type="button" @click="openModal = false" class="btn btn-secondary flex-1">Batal</button>
                        <button type="submit" class="btn btn-primary flex-1">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Return Confirmation -->
        <div x-show="showReturnModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="cancelReturn()">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Konfirmasi Pengembalian</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Masukkan tanggal pengembalian aktual:</p>
                <form :action="returnUrlBase + returnId + '/return'" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Pengembalian</label>
                        <input name="returned_at" type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" @click="cancelReturn()" class="btn btn-secondary flex-1">Batal</button>
                        <button type="submit" class="btn btn-success flex-1">Kembalikan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Delete Confirmation -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="cancelDelete()">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">Apakah Anda yakin ingin menghapus data peminjaman ini?</p>
                <div class="flex gap-2 mt-6">
                    <button type="button" @click="cancelDelete()" class="btn btn-secondary flex-1">Batal</button>
                    <form :action="deleteUrlBase + deleteId" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-full">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
