<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiLapor - Universitas Telkom</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-neu-bg font-sans antialiased text-neu-border min-h-screen flex flex-col items-center justify-center relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-10 left-10 w-32 h-32 bg-telkom-pink rounded-full border-2 border-neu-border shadow-[4px_4px_0px_rgba(0,0,0,1)] opacity-50 -z-10"></div>
    <div class="absolute bottom-10 right-10 w-48 h-48 bg-yellow-200 rounded-lg rotate-12 border-2 border-neu-border shadow-[4px_4px_0px_rgba(0,0,0,1)] opacity-50 -z-10"></div>
    <div class="absolute top-1/4 right-20 w-16 h-16 bg-blue-200 rounded-full border-2 border-neu-border shadow-[2px_2px_0px_rgba(0,0,0,1)] opacity-50 -z-10"></div>

    <div class="neu-card p-10 max-w-2xl text-center w-11/12">
        <h1 class="text-6xl font-bold mb-4">Si<span class="text-telkom-red">Lapor</span></h1>
        <p class="text-xl mb-8 font-semibold">Sistem Pelaporan dan Prioritas Perbaikan Fasilitas Kampus</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'staff' ? route('staff.dashboard') : route('pelapor.dashboard')) }}" class="neu-btn-primary text-lg px-8 py-3">
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="neu-btn-primary text-lg px-8 py-3">Login</a>
                <a href="{{ route('register') }}" class="neu-btn-secondary text-lg px-8 py-3">Register</a>
            @endauth
        </div>
    </div>
</body>
</html>
