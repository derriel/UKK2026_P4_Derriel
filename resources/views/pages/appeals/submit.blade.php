@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Mengajukan Appeal" />

    <x-common.component-card title="Mengajukan Appeal">
        <div class="space-y-4 text-sm text-gray-600 dark:text-gray-300">
            <p>Form pengajuan appeal dapat ditambahkan di sini untuk mengirim permohonan secara langsung.</p>
            <div class="rounded-lg border border-dashed border-gray-300 p-4 bg-gray-50 dark:bg-gray-900/50">
                <p class="font-medium">Placeholder pengajuan appeal</p>
                <p class="text-xs text-gray-500">Lengkapi form ini ketika backend pengajuan appeal sudah tersedia.</p>
            </div>
        </div>
    </x-common.component-card>
@endsection
