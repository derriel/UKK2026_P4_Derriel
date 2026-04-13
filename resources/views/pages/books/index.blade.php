@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Kelola Data Buku" />
<div class="space-y-6" x-data="{
            openModal: false,
            showDeleteModal: false,
            editingId: null,
            deleteId: null,
            createUrl: '{{ route('books.store') }}',
            updateUrlBase: '{{ url('/books') }}/',
            deleteUrlBase: '{{ url('/books') }}/',
            formData: {
                title: '',
                author_id: '',
                publisher_id: '',
                isbn: '',
                publication_year: '',
                category: '',
                description: '',
                stock: '',
            },

            openCreateModal() {
                this.editingId = null;
                this.formData = {
                    title: '',
                    author_id: '',
                    publisher_id: '',
                    isbn: '',
                    publication_year: '',
                    category: '',
                    description: '',
                    stock: '',
                };
                this.openModal = true;
            },

            editBook(book) {
                this.editingId = book.id;
                this.formData = {
                    title: book.title,
                    author_id: book.author_id,
                    publisher_id: book.publisher_id,
                    isbn: book.isbn,
                    publication_year: book.publication_year,
                    category: book.category,
                    description: book.description,
                    stock: book.stock,
                };
                this.openModal = true;
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
    <x-common.component-card title="Daftar Data Buku">
        <div class="flex justify-end mb-4">
            <button @click="openCreateModal()" class="btn btn-primary">
                + Tambah Buku
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
                        <th class="px-4 py-3 text-left font-semibold">Cover</th>
                        <th class="px-4 py-3 text-left font-semibold">Judul Buku</th>
                        <th class="px-4 py-3 text-left font-semibold">Pengarang</th>
                        <th class="px-4 py-3 text-left font-semibold">Penerbit</th>
                        <th class="px-4 py-3 text-left font-semibold">ISBN</th>
                        <th class="px-4 py-3 text-left font-semibold">Stok</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $index => $buku)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">
                            @if($buku->cover_image)
                            <img src="{{ asset('storage/' . $buku->cover_image) }}" alt="Cover {{ $buku->title }}" class="w-12 h-16 object-cover rounded-md">
                            @else
                            <div class="w-12 h-16 bg-gray-200 dark:bg-gray-700 rounded-md"></div>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $buku->title }}</td>
                        <td class="px-4 py-3">{{ $buku->author?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $buku->publisher?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $buku->isbn }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 bg-brand-100 text-brand-700 dark:bg-brand-900/30 dark:text-brand-300 rounded text-xs font-semibold">
                                {{ $buku->stock }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <button @click="editBook(@js(['id' => $buku->id, 'title' => $buku->title, 'author_id' => $buku->author_id, 'isbn' => $buku->isbn, 'publisher_id' => $buku->publisher_id, 'publication_year' => $buku->publication_year, 'category' => $buku->category, 'description' => $buku->description, 'stock' => $buku->stock]))" class="btn btn-sm btn-outline-primary">
                                    Edit
                                </button>
                                <button @click="openDeleteModal({{ $buku->id }})" class="btn btn-sm btn-outline-danger">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada data buku.</td>
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
                <span x-show="!editingId">Tambah Buku Baru</span>
                <span x-show="editingId">Edit Buku</span>
            </h2>

            <form x-ref="bookForm" method="POST" enctype="multipart/form-data" :action="editingId ? updateUrlBase + editingId : createUrl">
                @csrf
                <input type="hidden" name="_method" :value="editingId ? 'PUT' : 'POST'">

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Buku</label>
                        <input x-model="formData.title" name="title" type="text" placeholder="Masukkan judul buku" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pengarang</label>
                        <select name="author_id" x-model="formData.author_id"
                            class="w-full px-3 py-2 border rounded-lg">
                            <option value="">Pilih Pengarang</option>
                            @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Terbit</label>
                        <select name="publisher_id" x-model="formData.publisher_id" class="w-full px-3 py-2 border rounded-lg">
                            <option value="">Pilih Penerbit</option>
                            @foreach($publishers as $publisher)
                            <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                                {{ $publisher->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                        <input x-model="formData.category" name="category" type="text" placeholder="Masukkan kategori" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea x-model="formData.description" name="description" placeholder="Masukkan deskripsi" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Foto Sampul Buku</label>
                        <input name="cover_image" type="file" accept="image/*" class="w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100" />
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah foto sampul.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stok</label>
                        <input x-model="formData.stock" name="stock" type="number" placeholder="Masukkan jumlah stok" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    </div>
                </div>

                <div class="flex gap-2 mt-6">
                    <button type="button" @click="openModal = false" class="btn btn-secondary flex-1">Batal</button>
                    <button type="submit" class="btn btn-primary flex-1">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete Confirmation -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="cancelDelete()">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">Apakah Anda yakin ingin menghapus buku ini?</p>
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