@extends('layouts.app')

@section('content')
@push('styles')
<style>
    body {
        overflow: hidden;
    }
</style>
@endpush
<div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900">
    <div class="w-full max-w-xl bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">

        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6 text-center">
            Edit Profile
        </h1>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">
                    Name
                </label>
                <input type="text" name="name" value="{{ Auth::user()->name }}"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">
                    Email
                </label>
                <input type="email" name="email" value="{{ Auth::user()->email }}"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <!-- Photo -->
            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">
                    Photo
                </label>
                <input type="file" name="photo"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 
                    file:rounded-lg file:border-0 file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <!-- Button -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition duration-200">
                    Update Profile
                </button>
            </div>

        </form>
    </div>
</div>
@endsection