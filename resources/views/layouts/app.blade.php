<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kostify</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside id="sidebar"
            class="w-64 bg-white p-4 flex-col fixed inset-y-0 left-0 z-30 transform -translate-x-full transition-transform duration-300 ease-in-out md:relative md:translate-x-0 md:flex shadow-lg md:shadow-none">
            {{-- Logo --}}
            <div class="mb-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="h-6 w-6 text-gray-900">
                        <path d="m8 3 4 8 5-5 5 15H2L8 3z"></path>
                    </svg>
                    <h2 class="text-xl font-bold text-gray-900">Kostify</h2>
                </a>
            </div>
            {{-- Navigasi --}}
            <nav class="flex-1">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 transition-all hover:text-gray-900 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : '' }}">
                            Dashboard
                        </a>
                    </li>
                    @if (auth()->user()->role === 'ADMIN')
                        <li>
                            <a href="{{ route('rooms.index') }}"
                                class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 transition-all hover:text-gray-900 hover:bg-gray-100 {{ request()->routeIs('rooms.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                Manajemen Kamar
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('penghunis.index') }}"
                                class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 transition-all hover:text-gray-900 hover:bg-gray-100 {{ request()->routeIs('penghunis.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                Manajemen Penghuni
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('billings.index') }}"
                                class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 transition-all hover:text-gray-900 hover:bg-gray-100 {{ request()->routeIs('billings.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                Manajemen Tagihan
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
            {{-- Logout --}}
            <div class="mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full text-left flex items-center gap-3 rounded-lg px-3 py-2 text-gray-600 transition-all hover:text-gray-900 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex flex-col flex-1">
            {{-- Header Mobile --}}
            <header
                class="sticky top-0 z-10 border-b p-4 grid grid-cols-3 items-center bg-white/80 backdrop-blur-lg md:hidden">
                {{-- Tombol Menu (Kiri) --}}
                <div class="flex justify-start">
                    <button id="menu-btn" class="p-2 -ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="h-6 w-6">
                            <line x1="4" x2="20" y1="12" y2="12" />
                            <line x1="4" x2="20" y1="6" y2="6" />
                            <line x1="4" x2="20" y1="18" y2="18" />
                        </svg>
                    </button>
                </div>
                {{-- Judul Aplikasi (Tengah) --}}
                <div class="flex justify-center">
                    <h1 class="text-lg font-bold">Kostify</h1>
                </div>
                {{-- Placeholder (Kanan, agar judul tetap di tengah) --}}
                <div class="flex justify-end"></div>
            </header>

            <main class="p-4 sm:p-6 flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuBtn = document.getElementById('menu-btn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }

            if (menuBtn) {
                menuBtn.addEventListener('click', toggleSidebar);
            }
            if (overlay) {
                overlay.addEventListener('click', toggleSidebar);
            }
        });
    </script>
</body>

</html>
