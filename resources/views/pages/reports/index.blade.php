@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Laporan" />
    <div class="space-y-6">
        <!-- Report Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-common.component-card title="Total Buku">
                <div class="text-center">
                    <p class="text-3xl font-bold text-brand-500">45</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Total buku di perpustakaan</p>
                </div>
            </x-common.component-card>

            <x-common.component-card title="Total Anggota">
                <div class="text-center">
                    <p class="text-3xl font-bold text-green-500">23</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Total anggota terdaftar</p>
                </div>
            </x-common.component-card>

            <x-common.component-card title="Peminjaman Aktif">
                <div class="text-center">
                    <p class="text-3xl font-bold text-yellow-500">8</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Buku sedang dipinjam</p>
                </div>
            </x-common.component-card>

            <x-common.component-card title="Buku Tersedia">
                <div class="text-center">
                    <p class="text-3xl font-bold text-blue-500">37</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Buku siap dipinjam</p>
                </div>
            </x-common.component-card>
        </div>

        <!-- Laporan Peminjaman -->
        <x-common.component-card title="Laporan Peminjaman Bulanan">
            <div class="mb-4 flex gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan</label>
                    <select class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">-- Pilih Bulan --</option>
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun</label>
                    <select class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">-- Pilih Tahun --</option>
                        <option value="2023" selected>2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button class="btn btn-primary">
                        Tampilkan
                    </button>
                    <button class="btn btn-secondary">
                        Cetak
                    </button>
                </div>
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
                            <th class="px-4 py-3 text-left font-semibold">Durasi (hari)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach([
                            (object)['id' => 1, 'anggota' => 'Budi Santoso', 'buku' => 'Clean Code', 'tgl_pinjam' => '2024-01-05', 'tgl_kembali' => '2024-01-12', 'durasi' => 7],
                            (object)['id' => 2, 'anggota' => 'Siti Nurhaliza', 'buku' => 'Design Patterns', 'tgl_pinjam' => '2024-01-03', 'tgl_kembali' => '2024-01-10', 'durasi' => 7],
                            (object)['id' => 3, 'anggota' => 'Ahmad Wijaya', 'buku' => 'The Pragmatic Programmer', 'tgl_pinjam' => '2024-01-08', 'tgl_kembali' => '2024-01-15', 'durasi' => 7],
                            (object)['id' => 4, 'anggota' => 'Rini Pratama', 'buku' => 'Clean Code', 'tgl_pinjam' => '2024-01-15', 'tgl_kembali' => '2024-01-22', 'durasi' => 7],
                        ] as $index => $laporan)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">{{ $laporan->anggota }}</td>
                                <td class="px-4 py-3">{{ $laporan->buku }}</td>
                                <td class="px-4 py-3">{{ $laporan->tgl_pinjam }}</td>
                                <td class="px-4 py-3">{{ $laporan->tgl_kembali }}</td>
                                <td class="px-4 py-3">{{ $laporan->durasi }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-common.component-card>

        <!-- Laporan Stok Buku -->
        <x-common.component-card title="Laporan Stok Buku">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">No</th>
                            <th class="px-4 py-3 text-left font-semibold">Judul Buku</th>
                            <th class="px-4 py-3 text-left font-semibold">Pengarang</th>
                            <th class="px-4 py-3 text-left font-semibold">Stok Awal</th>
                            <th class="px-4 py-3 text-left font-semibold">Terpinjam</th>
                            <th class="px-4 py-3 text-left font-semibold">Stok Akhir</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach([
                            (object)['id' => 1, 'judul' => 'Clean Code', 'pengarang' => 'Robert C. Martin', 'stok_awal' => 10, 'terpinjam' => 2, 'stok_akhir' => 8, 'status' => 'Normal'],
                            (object)['id' => 2, 'judul' => 'The Pragmatic Programmer', 'pengarang' => 'David Thomas', 'stok_awal' => 5, 'terpinjam' => 3, 'stok_akhir' => 2, 'status' => 'Rendah'],
                            (object)['id' => 3, 'judul' => 'Design Patterns', 'pengarang' => 'Gang of Four', 'stok_awal' => 3, 'terpinjam' => 2, 'stok_akhir' => 1, 'status' => 'Kritis'],
                        ] as $index => $stok)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">{{ $stok->judul }}</td>
                                <td class="px-4 py-3">{{ $stok->pengarang }}</td>
                                <td class="px-4 py-3">{{ $stok->stok_awal }}</td>
                                <td class="px-4 py-3">{{ $stok->terpinjam }}</td>
                                <td class="px-4 py-3">{{ $stok->stok_akhir }}</td>
                                <td class="px-4 py-3">
                                    @if($stok->status === 'Normal')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 rounded text-xs font-semibold">
                                            {{ $stok->status }}
                                        </span>
                                    @elseif($stok->status === 'Rendah')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 rounded text-xs font-semibold">
                                            {{ $stok->status }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 rounded text-xs font-semibold">
                                            {{ $stok->status }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-common.component-card>

        <!-- Laporan Anggota Aktif -->
        <x-common.component-card title="Daftar Anggota dengan Peminjaman Terbanyak">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">No</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Anggota</th>
                            <th class="px-4 py-3 text-left font-semibold">No. Identitas</th>
                            <th class="px-4 py-3 text-left font-semibold">Jumlah Peminjaman</th>
                            <th class="px-4 py-3 text-left font-semibold">Buku Sedang Dipinjam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach([
                            (object)['id' => 1, 'nama' => 'Budi Santoso', 'identitas' => '3273081203000001', 'total_pinjam' => 12, 'dipinjam_sekarang' => 1],
                            (object)['id' => 2, 'nama' => 'Siti Nurhaliza', 'identitas' => '3275022404000002', 'total_pinjam' => 8, 'dipinjam_sekarang' => 2],
                            (object)['id' => 3, 'nama' => 'Ahmad Wijaya', 'identitas' => '3272051705000003', 'total_pinjam' => 6, 'dipinjam_sekarang' => 0],
                        ] as $index => $anggota)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">{{ $anggota->nama }}</td>
                                <td class="px-4 py-3">{{ $anggota->identitas }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 rounded text-xs font-semibold">
                                        {{ $anggota->total_pinjam }}x
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($anggota->dipinjam_sekarang > 0)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 rounded text-xs font-semibold">
                                            {{ $anggota->dipinjam_sekarang }}
                                        </span>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-common.component-card>
    </div>
@endsection
