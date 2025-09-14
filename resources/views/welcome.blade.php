<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kostify - Manajemen Kos Modern</title>
    @vite('resources/css/app.css')
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">
    <div class="flex flex-col min-h-screen">
        {{-- Header --}}
        <header class="sticky top-0 z-50 w-full border-b bg-white/80 backdrop-blur-lg">
            <div class="container mx-auto flex h-16 items-center px-4 sm:px-6 lg:px-8">
                <a href="/" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="h-6 w-6 text-slate-900">
                        <path d="m8 3 4 8 5-5 5 15H2L8 3z"></path>
                    </svg>
                    <span class="text-lg font-bold">Kostify</span>
                </a>
                <nav class="ml-auto flex items-center gap-2">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors h-10 px-4 py-2 hover:bg-slate-100">Login</a>
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors bg-slate-900 text-white shadow hover:bg-slate-800 h-10 px-4 py-2">
                        Daftar Gratis
                    </a>
                </nav>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-1">
            {{-- Hero Section --}}
            <section class="relative">
                <div class="absolute inset-0 bottom-1/3 bg-gradient-to-b from-blue-50 via-white to-slate-50"></div>
                {{-- PENYESUAIAN PADDING DI SINI --}}
                <div class="container relative mx-auto px-4 py-20 sm:px-6 lg:px-8 sm:py-24 lg:py-32">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        {{-- Konten Teks --}}
                        <div class="text-center lg:text-left">
                            <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl md:text-6xl">
                                <span class="block">Manajemen Kos</span>
                                <span class="block text-blue-600">Jadi Lebih Mudah.</span>
                            </h1>
                            <p class="mt-4 max-w-md mx-auto text-lg text-slate-600 sm:text-xl md:mt-5 md:max-w-3xl">
                                Kostify adalah solusi lengkap untuk mengelola kamar, penghuni, dan tagihan kos Anda
                                secara efisien dan modern.
                            </p>
                            <div class="mt-8 flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                                <a href="{{ route('register') }}"
                                    class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-base font-medium transition-colors bg-blue-600 text-white shadow-lg hover:bg-blue-700 h-12 px-6 py-3">
                                    Mulai Sekarang
                                </a>
                                <a href="{{ route('login') }}"
                                    class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-base font-medium transition-colors bg-white text-slate-900 shadow-lg hover:bg-slate-100 border h-12 px-6 py-3">
                                    Masuk ke Dashboard
                                </a>
                            </div>
                        </div>
                        {{-- Visual Mockup --}}
                        <div class="hidden lg:block">
                            <div class="relative w-full max-w-md mx-auto">
                                <div
                                    class="absolute inset-0 bg-gradient-to-tr from-blue-400 to-indigo-600 rounded-2xl transform -rotate-6 transition hover:rotate-0 hover:scale-105 duration-500">
                                </div>
                                <div class="relative bg-white p-6 rounded-2xl shadow-xl border">
                                    <div class="flex items-center justify-between pb-4 border-b">
                                        <p class="font-semibold">Dashboard</p>
                                        <div class="flex space-x-1.5">
                                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                                        </div>
                                    </div>
                                    <div class="mt-4 space-y-3">
                                        <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
                                            <div class="w-10 h-10 bg-green-200 rounded-md"></div>
                                            <div class="flex-1 space-y-1">
                                                <div class="h-3 bg-green-200 rounded"></div>
                                                <div class="h-2 w-2/3 bg-green-200 rounded"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                                            <div class="w-10 h-10 bg-blue-200 rounded-md"></div>
                                            <div class="flex-1 space-y-1">
                                                <div class="h-3 bg-blue-200 rounded"></div>
                                                <div class="h-2 w-2/3 bg-blue-200 rounded"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Fitur Unggulan --}}
            <section class="py-16 sm:py-24 bg-white">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Fitur Unggulan</h2>
                        <p class="mt-4 max-w-2xl mx-auto text-lg text-slate-600">
                            Semua yang Anda butuhkan untuk manajemen kos yang efisien dalam satu platform.
                        </p>
                    </div>
                    <div class="mt-12 grid gap-8 md:grid-cols-3">
                        <div class="p-8 rounded-xl border bg-slate-50/50">
                            <div
                                class="inline-flex items-center justify-center h-12 w-12 rounded-lg bg-blue-100 text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    class="h-6 w-6">
                                    <path d="M2 21v-2a4 4 0 0 1 4-4h12a4 4 0 0 1 4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </div>
                            <h3 class="mt-6 text-xl font-bold">Manajemen Kamar</h3>
                            <p class="mt-2 text-base text-slate-600">Pantau status ketersediaan, tipe, dan harga setiap
                                kamar dengan mudah.</p>
                        </div>
                        <div class="p-8 rounded-xl border bg-slate-50/50">
                            <div
                                class="inline-flex items-center justify-center h-12 w-12 rounded-lg bg-green-100 text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    class="h-6 w-6">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            </div>
                            <h3 class="mt-6 text-xl font-bold">Kelola Penghuni</h3>
                            <p class="mt-2 text-base text-slate-600">Simpan data penghuni, kelola dokumen, dan catat
                                riwayat sewa secara terpusat.</p>
                        </div>
                        <div class="p-8 rounded-xl border bg-slate-50/50">
                            <div
                                class="inline-flex items-center justify-center h-12 w-12 rounded-lg bg-yellow-100 text-yellow-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    class="h-6 w-6">
                                    <rect width="20" height="14" x="2" y="5" rx="2" />
                                    <line x1="2" x2="22" y1="10" y2="10" />
                                </svg>
                            </div>
                            <h3 class="mt-6 text-xl font-bold">Tagihan & Pembayaran</h3>
                            <p class="mt-2 text-base text-slate-600">Buat tagihan otomatis dan konfirmasi pembayaran
                                dengan mudah.</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        {{-- Footer --}}
        <footer class="bg-slate-900 text-slate-400">
            <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center text-sm">
                &copy; {{ date('Y') }} Kostify. Seluruh hak cipta dilindungi.
            </div>
        </footer>
    </div>
</body>

</html>
