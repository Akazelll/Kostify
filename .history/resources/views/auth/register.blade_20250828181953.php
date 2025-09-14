<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Kostify</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center">Daftar Akun Baru</h2>
            <p class="text-center text-gray-500 mb-4">Buat akun untuk memulai.</p>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="grid gap-4">
                    <div class="grid gap-2">
                        <label for="name">Nama</label>
                        <input id="name" name="name" placeholder="Nama Lengkap" required class="border rounded-md px-3 py-2">
                    </div>
                    <div class="grid gap-2">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" placeholder="m@example.com" required class="border rounded-md px-3 py-2">
                    </div>
                    <div class="grid gap-2">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" required class="border rounded-md px-3 py-2">
                    </div>
                    <div class="grid gap-2">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="border rounded-md px-3 py-2">
                    </div>
                    <button type="submit" class="w-full bg-black text-white py-2 rounded-md">Buat Akun</button>
                </div>
            </form>
            <div class="mt-4 text-center text-sm">
                Sudah punya akun? <a href="{{ route('login') }}" class="underline">Login</a>
            </div>
        </div>
    </div>
</body>
</html>