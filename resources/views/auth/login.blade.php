<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kostify</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center">Login</h2>
            <p class="text-center text-gray-500 mb-4">Masukkan email Anda untuk login.</p>

            @if ($errors->any())
                <div class="mb-4 text-red-500">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="grid gap-4">
                    <div class="grid gap-2">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" placeholder="m@example.com" required class="border rounded-md px-3 py-2">
                    </div>
                    <div class="grid gap-2">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" required class="border rounded-md px-3 py-2">
                    </div>
                    <button type="submit" class="w-full bg-black text-white py-2 rounded-md">Login</button>
                </div>
            </form>
            <div class="mt-4 text-center text-sm">
                Belum punya akun? <a href="{{ route('register') }}" class="underline">Daftar</a>
            </div>
        </div>
    </div>
</body>
</html>