@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Kelola Data Users" />
    <div class="space-y-6" x-data="{
            openUserModal: false,
            showUserDeleteModal: false,
            editingUserId: null,
            deleteUserId: null,
            createUserUrl: '{{ route('users.store') }}',
            updateUserUrlBase: '{{ url('/users') }}/',
            deleteUserUrlBase: '{{ url('/users') }}/',
            userFormData: {
                name: '',
                email: '',
                password: '',
                role_id: '',
            },

            openCreateUserModal() {
                this.editingUserId = null;
                this.userFormData = {
                    name: '',
                    email: '',
                    password: '',
                    role_id: '',
                };
                this.openUserModal = true;
            },

            editUser(user) {
                this.editingUserId = user.id;
                this.userFormData = {
                    name: user.name,
                    email: user.email,
                    password: '',
                    role_id: user.role_id,
                };
                this.openUserModal = true;
            },

            openDeleteUserModal(id) {
                this.deleteUserId = id;
                this.showUserDeleteModal = true;
            },

            cancelDeleteUser() {
                this.showUserDeleteModal = false;
                this.deleteUserId = null;
            },
        }">
        <x-common.component-card title="Data Users (Pengguna Sistem)">
            <div class="flex justify-end mb-4">
                <button @click="openCreateUserModal()" class="btn btn-primary">
                    + Tambah User
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
                        <th class="px-4 py-3 text-left font-semibold">Foto</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold">Email</th>
                        <th class="px-4 py-3 text-left font-semibold">Role</th>
                        <th class="px-4 py-3 text-left font-semibold">Tgl. Daftar</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">
                                    @if($user->photo)
                                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto {{ $user->name }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700"></div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $user->name }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 rounded text-xs font-semibold">
                                        {{ $user->role?->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $user->created_at?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center gap-2">
                                        <button @click="editUser(@js(['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role_id' => $user->role_id]))" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </button>
                                        <button @click="openDeleteUserModal({{ $user->id }})" class="btn btn-sm btn-outline-danger">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada data user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-common.component-card>

        <!-- Modal Create/Edit User -->
        <div x-show="openUserModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="openUserModal = false">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96 max-h-96 overflow-y-auto">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                    <span x-show="!editingUserId">Tambah User Baru</span>
                    <span x-show="editingUserId">Edit User</span>
                </h2>

                <form x-ref="userForm" method="POST" enctype="multipart/form-data" :action="editingUserId ? updateUserUrlBase + editingUserId : createUserUrl">
                    @csrf
                    <input type="hidden" name="_method" :value="editingUserId ? 'PUT' : 'POST'">

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                            <input x-model="userFormData.name" name="name" type="text" placeholder="Masukkan nama pengguna" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input x-model="userFormData.email" name="email" type="email" placeholder="Masukkan email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Foto Profil</label>
                            <input name="photo" type="file" accept="image/*" class="w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100" />
                            <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah foto.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password <span x-show="editingUserId" class="text-xs text-gray-500">(kosongkan jika tidak ingin mengubah)</span></label>
                            <input x-model="userFormData.password" name="password" type="password" placeholder="Masukkan password" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" :required="!editingUserId">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                            <select x-model="userFormData.role_id" name="role_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                                <option value="">Pilih Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-6">
                        <button type="button" @click="openUserModal = false" class="btn btn-secondary flex-1">Batal</button>
                        <button type="submit" class="btn btn-primary flex-1">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Delete User Confirmation -->
        <div x-show="showUserDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="cancelDeleteUser()">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Konfirmasi Hapus User</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">Apakah Anda yakin ingin menghapus user ini?</p>
                <div class="flex gap-2 mt-6">
                    <button type="button" @click="cancelDeleteUser()" class="btn btn-secondary flex-1">Batal</button>
                    <form :action="deleteUserUrlBase + deleteUserId" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-full">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
