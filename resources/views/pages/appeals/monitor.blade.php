@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Memantau Appeal" />

    <x-common.component-card title="Memantau Appeal">
        <div class="space-y-4 text-sm text-gray-600 dark:text-gray-300">
            <p>Gunakan halaman ini untuk melihat status dan detail appeal yang sedang diproses.</p>
            <div class="rounded-lg border border-dashed border-gray-300 p-4 bg-gray-50 dark:bg-gray-900/50">
                <p class="font-medium">Placeholder appeal monitoring</p>
                <p class="text-xs text-gray-500">Data appeal akan ditampilkan di sini setelah fitur terintegrasi dengan backend.</p>
            </div>
        </div>
    </x-common.component-card>
@endsection
