@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Kelola Rak Buku" />
<div class="space-y-6" x-data="{
            openModal: false,
            editingId: null,
            formData: {
                name: '',
                location: '',
                description: '',
            },

            openCreateModal() {
                this.editingId = null;
                this.formData = {
                    name: '',
                    location: '',
                    description: '',
                };
                this.openModal = true;
            },

            editRack(rack) {
                this.editingId = rack.id;
                this.formData = {
                    name: rack.name,
                    location: rack.location,
                    description: rack.description,
                };
                this.openModal = true;
            },
        }">
    <x-common.component-card title="Daftar Rak Buku">
        <div class="flex justify-end mb-4">
            <button @click="openCreateModal()" class="btn btn-primary">
                + Tambah Rak
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
                        <th class="px-4 py-3 text-left font-semibold">Nama Rak</th>
                        <th class="px-4 py-3 text-left font-semibold">Lokasi</th>
                        <th class="px-4 py-3 text-left font-semibold">Deskripsi</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($racks as $index => $rack)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $rack->name }}</td>
                        <td class="px-4 py-3">{{ $rack->location ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $rack->description ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <button @click="editRack(@js(['id' => $rack->id, 'name' => $rack->name, 'location' => $rack->location, 'description' => $rack->description]))" class="btn btn-sm btn-outline-primary">Edit</button>
                                <form action="{{ route('racks.destroy', $rack) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada rak buku.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-common.component-card>

    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="openModal = false">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                <span x-show="!editingId">Tambah Rak Buku</span>
                <span x-show="editingId">Edit Rak Buku</span>
            </h2>

            <form method="POST" :action="editingId ? '{{ url('/racks') }}/' + editingId : '{{ route('racks.store') }}'">
                @csrf
                <input type="hidden" name="_method" :value="editingId ? 'PUT' : 'POST'">

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Rak</label>
                        <input x-model="formData.name" name="name" type="text" placeholder="Masukkan nama rak" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lokasi</label>
                        <input x-model="formData.location" name="location" type="text" placeholder="Contoh: Lantai 1, Blok A" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea x-model="formData.description" name="description" placeholder="Deskripsi singkat tentang rak ini" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" rows="2"></textarea>
                    </div>
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