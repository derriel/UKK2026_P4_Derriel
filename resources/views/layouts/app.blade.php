<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} | TailAdmin - Laravel Tailwind CSS Admin Dashboard Template</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Alpine.js -->
    {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    <!-- Theme Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' :
                        'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    const html = document.documentElement;
                    const body = document.body;
                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                        body.classList.add('dark', 'bg-gray-900');
                    } else {
                        html.classList.remove('dark');
                        body.classList.remove('dark', 'bg-gray-900');
                    }
                }
            });

            Alpine.store('sidebar', {
                // Initialize based on screen size
                isExpanded: window.innerWidth >= 1280, // true for desktop, false for mobile
                isMobileOpen: false,
                isHovered: false,

                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    // When toggling desktop sidebar, ensure mobile menu is closed
                    this.isMobileOpen = false;
                },

                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                    // Don't modify isExpanded when toggling mobile menu
                },

                setMobileOpen(val) {
                    this.isMobileOpen = val;
                },

                setHovered(val) {
                    // Only allow hover effects on desktop when sidebar is collapsed
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                }
            });
        });
    </script>

    <!-- Apply dark mode immediately to prevent flash -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark', 'bg-gray-900');
            } else {
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('dark', 'bg-gray-900');
            }
        })();
    </script>
    <style>[x-cloak] { display: none !important; }</style>
</head>

<body
    x-data="{ 'loaded': true}"
    x-init="$store.sidebar.isExpanded = window.innerWidth >= 1280;
    const checkMobile = () => {
        if (window.innerWidth < 1280) {
            $store.sidebar.setMobileOpen(false);
            $store.sidebar.isExpanded = false;
        } else {
            $store.sidebar.isMobileOpen = false;
            $store.sidebar.isExpanded = true;
        }
    };
    window.addEventListener('resize', checkMobile);">

    {{-- preloader --}}
    <x-common.preloader/>
    {{-- preloader end --}}

    <div class="min-h-screen xl:flex">
        @include('layouts.backdrop')
        @include('layouts.sidebar')

        <div class="flex-1 transition-all duration-300 ease-in-out"
            :class="{
                'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'ml-0': $store.sidebar.isMobileOpen
            }">
            <!-- app header start -->
            @include('layouts.app-header')
            <!-- app header end -->
            <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                @yield('content')
            </div>
        </div>

    </div>

</body>

@stack('scripts')
@if(request()->routeIs('dashboard'))
<script>
setInterval(() => {
    fetch("{{ route('user.status') }}")
        .then(res => res.json())
        .then(data => {
            // Update Data Users tab di admin dashboard
            const tbody = document.querySelector('tbody');
            if (tbody) {
                tbody.innerHTML = data.filter(user => user.role !== 'member').slice(0, 10).map(user => `
                    <tr>
                        <td class="px-4 py-4 text-gray-900 dark:text-white">${user.name}</td>
                        <td class="px-4 py-4">${user.email}</td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold
                                ${user.status === 'Online'
                                    ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                    : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300'}">
                                <span class="w-2 h-2 rounded-full ${user.status === 'Online' ? 'bg-green-400' : 'bg-gray-400'}"></span>
                                ${user.status}
                            </span>
                        </td>
                    </tr>
                `).join('') || '<tr><td class="px-4 py-6 text-center text-gray-500 dark:text-gray-400" colspan="3">Belum ada data user yang tersedia.</td></tr>';
            }
        })
        .catch(error => console.error('Error updating user statuses:', error));
}, 5000); // refresh tiap 5 detik
</script>
@elseif(request()->routeIs('dashboard_petugas'))
<script>
setInterval(() => {
    fetch("{{ route('user.status') }}")
        .then(res => res.json())
        .then(data => {
            // Update Data Siswa di petugas dashboard
            const tbody = document.querySelector('#siswa-tbody');
            if (tbody) {
                const membersData = data.filter(user => user.role === 'member');
                tbody.innerHTML = membersData.slice(0, 10).map(member => `
                    <tr>
                        <td class="px-4 py-4 text-gray-900 dark:text-white">${member.name}</td>
                        <td class="px-4 py-4">${member.email}</td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold
                                ${member.status === 'Online'
                                    ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                    : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300'}">
                                <span class="w-2 h-2 rounded-full ${member.status === 'Online' ? 'bg-green-400' : 'bg-gray-400'}"></span>
                                ${member.status}
                            </span>
                        </td>
                    </tr>
                `).join('') || '<tr><td class="px-4 py-6 text-center text-gray-500 dark:text-gray-400" colspan="3">Belum ada data siswa yang tersedia.</td></tr>';
            }
        })
        .catch(error => console.error('Error updating siswa statuses:', error));
}, 5000); // refresh tiap 5 detik
</script>
@endif
</html>
