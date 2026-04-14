@extends('layouts.petugas-layout')

@section('content')
<x-common.page-breadcrumb pageTitle="Kelola Data Penerbit" />
<div class="space-y-6" x-data="{
            openModal: false,
            showDeleteModal: false,
            editingId: null,
            deleteId: null,
            createUrl: '{{ route('publishers.store') }}',
            updateUrlBase: '{{ url('/publishers') }}/',
            deleteUrlBase: '{{ url('/publishers') }}/',
            formData: {
                name: '',
                city: '',
                phone: '',
                logoUrl: null,
            },

            openCreateModal() {
                this.editingId = null;
                this.formData = {
                    name: '',
                    city: '',
                    phone: '',
                    logoUrl: null,
                };
                this.openModal = true;
            },

            editPublisher(publisher) {
                this.editingId = publisher.id;
                this.formData = {
                    name: publisher.name,
                    city: publisher.city,
                    phone: publisher.phone,
                    logoUrl: publisher.logo,
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
    <x-common.component-card title="Daftar Data Penerbit">
        <div class="flex justify-end mb-4">
            <button @click="openCreateModal()" class="btn btn-primary">
                + Tambah Penerbit
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
                        <th class="px-4 py-3 text-left font-semibold">Nama Penerbit</th>
                        <th class="px-4 py-3 text-left font-semibold">Logo</th>
                        <th class="px-4 py-3 text-left font-semibold">Kota</th>
                        <th class="px-4 py-3 text-left font-semibold">Telepon</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($publishers as $index => $publisher)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $publisher->name }}</td>
                        <td class="px-4 py-3">
                            @if($publisher->logo)
                                <img src="{{ asset('storage/' . $publisher->logo) }}" alt="Logo {{ $publisher->name }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16"></div>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $publisher->city ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $publisher->phone ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <button @click="editPublisher(@js(['id' => $publisher->id, 'name' => $publisher->name, 'city' => $publisher->city, 'phone' => $publisher->phone, 'logo' => $publisher->logo ? asset('storage/' . $publisher->logo) : null]))" class="btn btn-sm btn-outline-primary">
                                    Edit
                                </button>
                                <button @click="openDeleteModal({{ $publisher->id }})" class="btn btn-sm btn-outline-danger">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada data penerbit.</td>
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
                <span x-show="!editingId">Tambah Penerbit Baru</span>
                <span x-show="editingId">Edit Penerbit</span>
            </h2>

            <form x-ref="publisherForm" method="POST" enctype="multipart/form-data" :action="editingId ? updateUrlBase + editingId : createUrl">
                @csrf
                <input type="hidden" name="_method" :value="editingId ? 'PUT' : 'POST'">

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Penerbit</label>
                        <input x-model="formData.name" name="name" type="text" placeholder="Masukkan nama penerbit" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo Penerbit</label>
                        <input name="logo" type="file" accept="image/*" class="w-full text-sm text-gray-500 file:border-0 file:bg-primary file:px-3 file:py-2 file:text-white file:rounded-lg">
                        <template x-if="formData.logoUrl">
                            <img :src="formData.logoUrl" alt="Logo saat ini" class="mt-3 w-24 h-24 object-cover rounded">
                        </template>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kota</label>
                        <input x-model="formData.city" name="city" type="text" placeholder="Masukkan kota" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
                        <input x-model="formData.phone" name="phone" type="text" placeholder="Masukkan nomor telepon" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
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
            <p class="text-sm text-gray-600 dark:text-gray-300">Apakah Anda yakin ingin menghapus penerbit ini?</p>
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