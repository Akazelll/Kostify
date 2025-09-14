<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kostify</title>
    @vite('resources/css/app.css')
</head>

<body class="antialiased">
    <div class="flex flex-col min-h-screen">
        <header class="px-4 lg:px-6 h-14 flex items-center">
            <a href="/" class="flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="h-6 w-6">
                    <path d="m8 3 4 8 5-5 5 15H2L8 3z"></path>
                </svg>
                <span class="ml-2 text-lg font-bold">Kostify</span>
            </a>
            <nav class="ml-auto flex gap-4 sm:gap-6">
                <a href="{{ route('login') }}" class="text-sm font-medium hover:underline underline-offset-4">Login</a>
                <a href="{{ route('register') }}" class="text-sm font-medium hover:underline underline-offset-4">Sign
                    Up</a>
            </nav>
        </header>
        <main class="flex-1">
            <section class="w-full py-12 md:py-24 lg:py-32 xl:py-48">
                <div class="container px-4 md:px-6">
                    <div class="flex flex-col items-center space-y-4 text-center">
                        <div class="space-y-2">
                            <h1 class="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl lg:text-6xl/none">
                                Manajemen Kos Menjadi Mudah
                            </h1>
                            <p class="mx-auto max-w-[700px] text-gray-500 md:text-xl dark:text-gray-400">
                                Kostify adalah solusi lengkap untuk mengelola kamar, penghuni, dan tagihan kos Anda
                                secara efisien.
                            </p>
                        </div>
                        <div class="space-x-4">
                            <a href="{{ route('dashboard') }}">
                                <button
                                    class="inline-flex h-9 items-center justify-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-gray-50 shadow transition-colors hover:bg-gray-900/90 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-gray-950 disabled:pointer-events-none disabled:opacity-50 dark:bg-gray-50 dark:text-gray-900 dark:hover:bg-gray-50/90 dark:focus-visible:ring-gray-300">
                                    Masuk ke Dashboard
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <footer class="flex flex-col gap-2 sm:flex-row py-6 w-full shrink-0 items-center px-4 md:px-6 border-t">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} Kostify. All rights reserved.
            </p>
        </footer>
    </div>
</body>

</html>
