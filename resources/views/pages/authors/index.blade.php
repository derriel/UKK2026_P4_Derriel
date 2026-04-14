@extends('layouts.petugas-layout')

@section('content')
<x-common.page-breadcrumb pageTitle="Kelola Data Pengarang" />
<div class="space-y-6" x-data="{
            openModal: false,
            showDeleteModal: false,
            editingId: null,
            deleteId: null,
            createUrl: '{{ route('authors.store') }}',
            updateUrlBase: '{{ url('/authors') }}/',
            deleteUrlBase: '{{ url('/authors') }}/',
            formData: {
                name: '',
                email: '',
                birth_date: '',
                nationality: '',
                biography: '',
            },

            openCreateModal() {
                this.editingId = null;
                this.formData = {
                    name: '',
                    email: '',
                    birth_date: '',
                    nationality: '',
                    biography: '',
                };
                this.openModal = true;
            },

            editAuthor(author) {
                this.editingId = author.id;
                this.formData = {
                    name: author.name,
                    email: author.email,
                    birth_date: author.birth_date,
                    nationality: author.nationality,
                    biography: author.biography,
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
    <x-common.component-card title="Daftar Data Pengarang">
        <div class="flex justify-end mb-4">
            <button @click="openCreateModal()" class="btn btn-primary">
                + Tambah Pengarang
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
                        <th class="px-4 py-3 text-left font-semibold">Nama Pengarang</th>
                        <th class="px-4 py-3 text-left font-semibold">Email</th>
                        <th class="px-4 py-3 text-left font-semibold">Tanggal Lahir</th>
                        <th class="px-4 py-3 text-left font-semibold">Kewarganegaraan</th>
                        <th class="px-4 py-3 text-left font-semibold">Biografi</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($authors as $index => $author)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $author->name }}</td>
                        <td class="px-4 py-3">{{ $author->email ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $author->birth_date ? \Carbon\Carbon::parse($author->birth_date)->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-3">{{ $author->nationality ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $author->biography ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <button @click="editAuthor(@js(['id' => $author->id, 'name' => $author->name, 'email' => $author->email, 'birth_date' => $author->birth_date, 'nationality' => $author->nationality, 'biography' => $author->biography]))" class="btn btn-sm btn-outline-primary">
                                    Edit
                                </button>
                                <button @click="openDeleteModal({{ $author->id }})" class="btn btn-sm btn-outline-danger">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada data pengarang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-common.component-card>

    <!-- Modal Create/Edit -->
    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="openModal = false">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                <span x-show="!editingId">Tambah Pengarang Baru</span>
                <span x-show="editingId">Edit Pengarang</span>
            </h2>

            <form x-ref="authorForm" method="POST" :action="editingId ? updateUrlBase + editingId : createUrl">
                @csrf
                <input type="hidden" name="_method" :value="editingId ? 'PUT' : 'POST'">

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Pengarang</label>
                        <input x-model="formData.name" name="name" type="text" placeholder="Masukkan nama pengarang" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input x-model="formData.email" name="email" type="email" placeholder="Masukkan email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Lahir</label>
                        <input x-model="formData.birth_date" name="birth_date" type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kewarganegaraan</label>
                        <input x-model="formData.nationality" name="nationality" type="text" placeholder="Masukkan kewarganegaraan" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Biografi</label>
                        <textarea x-model="formData.biography" name="biography" placeholder="Masukkan biografi" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" rows="3"></textarea>
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
            <p class="text-sm text-gray-600 dark:text-gray-300">Apakah Anda yakin ingin menghapus pengarang ini?</p>
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