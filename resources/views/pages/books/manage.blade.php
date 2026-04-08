@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Kelola Data Buku" />
    <div class="space-y-6">
        <x-common.component-card title="Daftar Data Buku">
            <div class="flex justify-end mb-4">
                <button @click="openModal = true" class="btn btn-primary">
                    + Tambah Buku
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">No</th>
                            <th class="px-4 py-3 text-left font-semibold">Judul Buku</th>
                            <th class="px-4 py-3 text-left font-semibold">Pengarang</th>
                            <th class="px-4 py-3 text-left font-semibold">Penerbit</th>
                            <th class="px-4 py-3 text-left font-semibold">ISBN</th>
                            <th class="px-4 py-3 text-left font-semibold">Stok</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach([
                            (object)['id' => 1, 'judul' => 'Clean Code', 'pengarang' => 'Robert C. Martin', 'penerbit' => 'Prentice Hall', 'isbn' => '978-0132350884', 'stok' => 5],
                            (object)['id' => 2, 'judul' => 'The Pragmatic Programmer', 'pengarang' => 'David Thomas', 'penerbit' => 'Addison-Wesley', 'isbn' => '978-0201616224', 'stok' => 3],
                            (object)['id' => 3, 'judul' => 'Design Patterns', 'pengarang' => 'Gang of Four', 'penerbit' => 'Addison-Wesley', 'isbn' => '978-0201633610', 'stok' => 2],
                        ] as $index => $buku)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">{{ $buku->judul }}</td>
                                <td class="px-4 py-3">{{ $buku->pengarang }}</td>
                                <td class="px-4 py-3">{{ $buku->penerbit }}</td>
                                <td class="px-4 py-3">{{ $buku->isbn }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-brand-100 text-brand-700 dark:bg-brand-900/30 dark:text-brand-300 rounded text-xs font-semibold">
                                        {{ $buku->stok }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center gap-2">
                                        <button @click="editBook({{ $buku->id }})" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </button>
                                        <button @click="deleteBook({{ $buku->id }})" class="btn btn-sm btn-outline-danger">
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
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                <span x-show="!editingId">Tambah Buku Baru</span>
                <span x-show="editingId">Edit Buku</span>
            </h2>
            
            <form @submit.prevent="submitForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Judul Buku
                        </label>
                        <input type="text" placeholder="Masukkan judul buku" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Pengarang
                        </label>
                        <input type="text" placeholder="Masukkan nama pengarang" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Penerbit
                        </label>
                        <input type="text" placeholder="Masukkan nama penerbit" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            ISBN
                        </label>
                        <input type="text" placeholder="Masukkan ISBN" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Stok
                        </label>
                        <input type="number" placeholder="Masukkan jumlah stok" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
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
            Alpine.data('bookManager', () => ({
                openModal: false,
                editingId: null,
                
                editBook(id) {
                    this.editingId = id;
                    this.openModal = true;
                },
                
                deleteBook(id) {
                    if (confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
                        alert('Buku dengan ID ' + id + ' berhasil dihapus');
                    }
                },
                
                submitForm() {
                    if (this.editingId) {
                        alert('Buku berhasil diperbarui');
                    } else {
                        alert('Buku berhasil ditambahkan');
                    }
                    this.openModal = false;
                    this.editingId = null;
                }
            }));
        });
    </script>
@endsection
