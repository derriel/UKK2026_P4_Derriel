@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Kelola Data Anggota" />
    <div class="space-y-6">
        <x-common.component-card title="Daftar Data Anggota">
            <div class="flex justify-end mb-4">
                <button @click="openModal = true" class="btn btn-primary">
                    + Tambah Anggota
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">No</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Anggota</th>
                            <th class="px-4 py-3 text-left font-semibold">No. Identitas</th>
                            <th class="px-4 py-3 text-left font-semibold">Email</th>
                            <th class="px-4 py-3 text-left font-semibold">No. Telepon</th>
                            <th class="px-4 py-3 text-left font-semibold">Alamat</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach([
                            (object)['id' => 1, 'nama' => 'Budi Santoso', 'identitas' => '3273081203000001', 'email' => 'budi@example.com', 'telepon' => '081234567890', 'alamat' => 'Jl. Merdeka No. 1, Jakarta'],
                            (object)['id' => 2, 'nama' => 'Siti Nurhaliza', 'identitas' => '3275022404000002', 'email' => 'siti@example.com', 'telepon' => '081234567891', 'alamat' => 'Jl. Sudirman No. 2, Bandung'],
                            (object)['id' => 3, 'nama' => 'Ahmad Wijaya', 'identitas' => '3272051705000003', 'email' => 'ahmad@example.com', 'telepon' => '081234567892', 'alamat' => 'Jl. Ahmad Yani No. 3, Surabaya'],
                        ] as $index => $anggota)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">{{ $anggota->nama }}</td>
                                <td class="px-4 py-3">{{ $anggota->identitas }}</td>
                                <td class="px-4 py-3">{{ $anggota->email }}</td>
                                <td class="px-4 py-3">{{ $anggota->telepon }}</td>
                                <td class="px-4 py-3">{{ $anggota->alamat }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center gap-2">
                                        <button @click="editMember({{ $anggota->id }})" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </button>
                                        <button @click="deleteMember({{ $anggota->id }})" class="btn btn-sm btn-outline-danger">
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
                <span x-show="!editingId">Tambah Anggota Baru</span>
                <span x-show="editingId">Edit Anggota</span>
            </h2>
            
            <form @submit.prevent="submitForm">
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nama Anggota
                        </label>
                        <input type="text" placeholder="Masukkan nama anggota" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            No. Identitas
                        </label>
                        <input type="text" placeholder="Masukkan nomor identitas" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Email
                        </label>
                        <input type="email" placeholder="Masukkan email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            No. Telepon
                        </label>
                        <input type="tel" placeholder="Masukkan nomor telepon" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Alamat
                        </label>
                        <textarea placeholder="Masukkan alamat" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" rows="2" required></textarea>
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
            Alpine.data('memberManager', () => ({
                openModal: false,
                editingId: null,
                
                editMember(id) {
                    this.editingId = id;
                    this.openModal = true;
                },
                
                deleteMember(id) {
                    if (confirm('Apakah Anda yakin ingin menghapus anggota ini?')) {
                        alert('Anggota dengan ID ' + id + ' berhasil dihapus');
                    }
                },
                
                submitForm() {
                    if (this.editingId) {
                        alert('Anggota berhasil diperbarui');
                    } else {
                        alert('Anggota berhasil ditambahkan');
                    }
                    this.openModal = false;
                    this.editingId = null;
                }
            }));
        });
    </script>
@endsection
