<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kostify</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <aside class="w-64 border-r bg-white p-4 flex flex-col">
            <div class="mb-6">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-black"><path d="m8 3 4 8 5-5 5 15H2L8 3z"></path></svg>
                    <h2 class="text-xl font-bold">Kostify</h2>
                </a>
            </div>
            <nav class="flex-1">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 transition-all hover:text-black">
                            Dashboard
                        </a>
                    </li>
                    @if(auth()->user()->role === 'ADMIN')
                        <li>
                            <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 transition-all hover:text-black">
                                Manajemen Kamar
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 transition-all hover:text-black">
                                Manajemen Penghuni
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 transition-all hover:text-black">
                                Manajemen Tagihan
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
            <div class="mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center gap-3 rounded-lg px-3 py-2 text-gray-500 transition-all hover:text-black">
                        Logout
                    </button>
                </form>
            </div>
        </aside>
        <div class="flex flex-col flex-1">
            <header class="border-b p-4 flex justify-end bg-white">
                </header>
            <main class="p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>