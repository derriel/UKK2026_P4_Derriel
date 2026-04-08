@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Kelola Peminjaman & Pengembalian" />
    <div class="space-y-6">
        <x-common.component-card title="Daftar Peminjaman & Pengembalian">
            <div class="flex justify-end mb-4">
                <button @click="openModal = true" class="btn btn-primary">
                    + Tambah Peminjaman
                </button>
            </div>

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
                        @foreach([
                            (object)['id' => 1, 'anggota' => 'Budi Santoso', 'buku' => 'Clean Code', 'tgl_pinjam' => '2024-01-15', 'tgl_kembali' => '2024-01-22', 'status' => 'Dipinjam'],
                            (object)['id' => 2, 'anggota' => 'Siti Nurhaliza', 'buku' => 'Design Patterns', 'tgl_pinjam' => '2024-01-10', 'tgl_kembali' => '2024-01-17', 'status' => 'Dikembalikan'],
                            (object)['id' => 3, 'anggota' => 'Ahmad Wijaya', 'buku' => 'The Pragmatic Programmer', 'tgl_pinjam' => '2024-01-18', 'tgl_kembali' => null, 'status' => 'Dipinjam'],
                        ] as $index => $peminjaman)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">{{ $peminjaman->anggota }}</td>
                                <td class="px-4 py-3">{{ $peminjaman->buku }}</td>
                                <td class="px-4 py-3">{{ $peminjaman->tgl_pinjam }}</td>
                                <td class="px-4 py-3">{{ $peminjaman->tgl_kembali ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if($peminjaman->status === 'Dipinjam')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 rounded text-xs font-semibold">
                                            {{ $peminjaman->status }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 rounded text-xs font-semibold">
                                            {{ $peminjaman->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center gap-2 flex-wrap">
                                        @if($peminjaman->status === 'Dipinjam')
                                            <button @click="returnBook({{ $peminjaman->id }})" class="btn btn-sm btn-success inline-flex items-center gap-2">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8 1V15M1 8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                Kembalikan
                                            </button>
                                        @endif
                                        <button @click="editBorrowing({{ $peminjaman->id }})" class="btn btn-sm btn-outline-primary inline-flex items-center gap-2">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.5 2L14 4.5L5 13.5H2.5V11L11.5 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            Edit
                                        </button>
                                        <button @click="deleteBorrowing({{ $peminjaman->id }})" class="btn btn-sm btn-outline-danger inline-flex items-center gap-2">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2 4H14M6.5 7V12M9.5 7V12M3 4L4 14C4 14.5304 4.21071 15.0391 4.58579 15.4142C4.96086 15.7893 5.46957 16 6 16H10C10.5304 16 11.0391 15.7893 11.4142 15.4142C11.7893 15.0391 12 14.5304 12 14L13 4M5.5 4V2.5C5.5 2.23478 5.60536 1.98043 5.79289 1.79289C5.98043 1.60536 6.23478 1.5 6.5 1.5H9.5C9.76522 1.5 10.0196 1.60536 10.2071 1.79289C10.3946 1.98043 10.5 2.23478 10.5 2.5V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-common.component-card>
    </div>

    <!-- Modal Create/Edit -->
    <div x-data="{ openModal: false, editingId: null }" x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="openModal = false">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96 max-h-96 overflow-y-auto">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                <span x-show="!editingId">Tambah Peminjaman Baru</span>
                <span x-show="editingId">Edit Peminjaman</span>
            </h2>
            
            <form @submit.prevent="submitForm">
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nama Anggota
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                            <option value="">-- Pilih Anggota --</option>
                            <option value="1">Budi Santoso</option>
                            <option value="2">Siti Nurhaliza</option>
                            <option value="3">Ahmad Wijaya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Judul Buku
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                            <option value="">-- Pilih Buku --</option>
                            <option value="1">Clean Code</option>
                            <option value="2">The Pragmatic Programmer</option>
                            <option value="3">Design Patterns</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal Peminjaman
                        </label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal Kembali
                        </label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Status
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Dipinjam">Dipinjam</option>
                            <option value="Dikembalikan">Dikembalikan</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex gap-2 mt-6">
                    <button type="button" @click="openModal = false" class="btn btn-secondary flex-1">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary flex-1">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('borrowingManager', () => ({
                openModal: false,
                editingId: null,
                
                editBorrowing(id) {
                    this.editingId = id;
                    this.openModal = true;
                },
                
                returnBook(id) {
                    if (confirm('Apakah Anda yakin buku ini akan dikembalikan?')) {
                        alert('Buku berhasil dikembalikan');
                    }
                },
                
                deleteBorrowing(id) {
                    if (confirm('Apakah Anda yakin ingin menghapus data peminjaman ini?')) {
                        alert('Data peminjaman dengan ID ' + id + ' berhasil dihapus');
                    }
                },
                
                submitForm() {
                    if (this.editingId) {
                        alert('Data peminjaman berhasil diperbarui');
                    } else {
                        alert('Data peminjaman berhasil ditambahkan');
                    }
                    this.openModal = false;
                    this.editingId = null;
                }
            }));
        });
    </script>
@endsection
