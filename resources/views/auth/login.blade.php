<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiLapor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-neu-bg font-sans antialiased text-neu-border min-h-screen flex items-center justify-center p-4">

    <div class="neu-card p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold mb-2">Si<span class="text-telkom-red">Lapor</span></h1>
            <p class="font-semibold text-gray-600">Login ke Akun Anda</p>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border-2 border-neu-border text-red-800 p-4 rounded-lg mb-6 shadow-neu font-semibold text-sm">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block font-bold mb-1" for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="neu-input w-full" placeholder="email@domain.com">
            </div>
            
            <div>
                <label class="block font-bold mb-1" for="password">Password</label>
                <input type="password" id="password" name="password" required class="neu-input w-full" placeholder="••••••••">
            </div>

            <button type="submit" class="neu-btn-primary w-full mt-4 text-lg">Login</button>
        </form>

        <div class="mt-6 text-center text-sm font-semibold">
            Belum punya akun? <a href="{{ route('register') }}" class="text-telkom-red underline hover:text-telkom-red-dark">Daftar sekarang</a>
        </div>
        <div class="mt-2 text-center text-xs text-gray-500">
            <a href="/" class="underline hover:text-neu-border">Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>
