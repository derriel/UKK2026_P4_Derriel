@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Daftar Data Siswa" />
    <div class="space-y-6" x-data="{
            // Siswa management
            openModal: false,
            showDeleteModal: false,
            editingId: null,
            deleteId: null,
            createUrl: '{{ route('members.store') }}',
            updateUrlBase: '{{ url('/members') }}/',
            deleteUrlBase: '{{ url('/members') }}/',
            formData: {
                id_siswa: '',
                nis: '',
                name: '',
                email: '',
                phone: '',
                address: '',
                kelas: '',
                jurusan: '',
            },

            // User management
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

            // Siswa functions
            openCreateModal() {
                this.editingId = null;
                this.formData = {
                    id_siswa: '',
                    nis: '',
                    name: '',
                    email: '',
                    phone: '',
                    address: '',
                    kelas: '',
                    jurusan: '',
                };
                this.openModal = true;
            },

            editMember(member) {
                this.editingId = member.id;
                this.formData = {
                    id_siswa: member.id_siswa,
                    nis: member.nis,
                    name: member.name,
                    email: member.email,
                    phone: member.phone,
                    address: member.address,
                    kelas: member.kelas,
                    jurusan: member.jurusan,
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

            // User functions
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
        <x-common.component-card title="Daftar Data Siswa">
            <div class="flex justify-end mb-4">
                <button @click="openCreateModal()" class="btn btn-primary">
                    + Tambah Siswa
                </button>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/30 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Data Members (Anggota Perpustakaan)</h3>
                <table class="w-full text-sm text-gray-700 dark:text-gray-300 mb-8">
                    <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">No</th>
                        <th class="px-4 py-3 text-left font-semibold">ID Siswa</th>
                        <th class="px-4 py-3 text-left font-semibold">NIS</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold">Email</th>
                        <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                        <th class="px-4 py-3 text-left font-semibold">Jurusan</th>
                        <th class="px-4 py-3 text-left font-semibold">No. Telepon</th>
                        <th class="px-4 py-3 text-left font-semibold">Alamat</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswa as $index => $anggota)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">{{ $anggota->id_siswa }}</td>
                                <td class="px-4 py-3">{{ $anggota->nis }}</td>
                                <td class="px-4 py-3">{{ $anggota->name }}</td>
                                <td class="px-4 py-3">{{ $anggota->email }}</td>
                                <td class="px-4 py-3">{{ $anggota->kelas }}</td>
                                <td class="px-4 py-3">{{ $anggota->jurusan }}</td>
                                <td class="px-4 py-3">{{ $anggota->phone }}</td>
                                <td class="px-4 py-3">{{ $anggota->address }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center gap-2">
                                        <button @click="editMember(@js(['id' => $anggota->id, 'id_siswa' => $anggota->id_siswa, 'nis' => $anggota->nis, 'name' => $anggota->name, 'email' => $anggota->email, 'phone' => $anggota->phone, 'address' => $anggota->address, 'kelas' => $anggota->kelas, 'jurusan' => $anggota->jurusan]))" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </button>
                                        <button @click="openDeleteModal({{ $anggota->id }})" class="btn btn-sm btn-outline-danger">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Belum ada data member.</td>
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
                    <span x-show="!editingId">Tambah Siswa</span>
                    <span x-show="editingId">Edit Siswa</span>
                </h2>
                
                <form x-ref="memberForm" method="POST" :action="editingId ? updateUrlBase + editingId : createUrl">
                    @csrf
                    <input type="hidden" name="_method" :value="editingId ? 'PUT' : 'POST'">

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Siswa</label>
                            <input x-model="formData.name" name="name" type="text" placeholder="Masukkan nama siswa" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input x-model="formData.email" name="email" type="email" placeholder="Masukkan email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NIS</label>
                            <input x-model="formData.nis" name="nis" type="text" placeholder="Masukkan NIS" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelas</label>
                            <select x-model="formData.kelas" name="kelas" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Pilih Kelas</option>
                                <option value="X">Kelas X</option>
                                <option value="XI">Kelas XI</option>
                                <option value="XII">Kelas XII</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jurusan</label>
                            <select x-model="formData.jurusan" name="jurusan" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Pilih Jurusan</option>
                                <option value="RPL">Rekayasa Perangkat Lunak</option>
                                <option value="TKJ">Teknik Komputer Jaringan</option>
                                <option value="MM">Multimedia</option>
                                <option value="AK">Akuntansi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. Telepon</label>
                            <input x-model="formData.phone" name="phone" type="tel" placeholder="Masukkan nomor telepon" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                            <textarea x-model="formData.address" name="address" placeholder="Masukkan alamat" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" rows="2"></textarea>
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
                <p class="text-sm text-gray-600 dark:text-gray-300">Apakah Anda yakin ingin menghapus anggota ini?</p>
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

        <!-- Modal Create/Edit User -->
        <div x-show="openUserModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="openUserModal = false">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96 max-h-96 overflow-y-auto">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                    <span x-show="!editingUserId">Tambah User Baru</span>
                    <span x-show="editingUserId">Edit User</span>
                </h2>
                
                <form x-ref="userForm" method="POST" :action="editingUserId ? updateUserUrlBase + editingUserId : createUserUrl">
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
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password<span x-show="editingUserId" class="text-xs text-gray-500"> (kosongkan jika tidak ingin mengubah)</span></label>
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
