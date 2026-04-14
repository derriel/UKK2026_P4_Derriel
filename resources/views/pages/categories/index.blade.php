@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Kelola Kategori Buku" />
<div class="space-y-6" x-data="{
            openModal: false,
            editingId: null,
            formData: {
                name: '',
            },

            openCreateModal() {
                this.editingId = null;
                this.formData = {
                    name: '',
                };
                this.openModal = true;
            },

            editCategory(category) {
                this.editingId = category.id;
                this.formData = {
                    name: category.name,
                };
                this.openModal = true;
            },
        }">
    <x-common.component-card title="Daftar Kategori Buku">
        <div class="flex justify-end mb-4">
            <button @click="openCreateModal()" class="btn btn-primary">
                + Tambah Kategori
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
                        <th class="px-4 py-3 text-left font-semibold">Nama Kategori</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <button @click="editCategory(@js(['id' => $category->id, 'name' => $category->name]))" class="btn btn-sm btn-outline-primary">Edit</button>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada kategori.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-common.component-card>

    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="openModal = false">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                <span x-show="!editingId">Tambah Kategori Baru</span>
                <span x-show="editingId">Edit Kategori</span>
            </h2>

            <form method="POST" :action="editingId ? '{{ url('/categories') }}/' + editingId : '{{ route('categories.store') }}'">
                @csrf
                <input type="hidden" name="_method" :value="editingId ? 'PUT' : 'POST'">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Kategori</label>
                    <input x-model="formData.name" name="name" type="text" placeholder="Masukkan nama kategori" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                </div>

                <div class="flex gap-2 mt-6">
                    <button type="button" @click="openModal = false" class="btn btn-secondary flex-1">Batal</button>
                    <button type="submit" class="btn btn-primary flex-1">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
