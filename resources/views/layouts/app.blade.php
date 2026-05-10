<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SiLapor - Sistem Pelaporan')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body class="font-sans antialiased text-neu-border">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-telkom-pink border-r-2 border-neu-border flex flex-col justify-between">
            <div>
                <div class="p-6 border-b-2 border-neu-border bg-white flex items-center justify-center">
                    <h1 class="text-2xl font-bold text-telkom-red tracking-tight">Si<span class="text-neu-border">Lapor</span></h1>
                </div>
                
                <nav class="p-4 space-y-2">
                    @if(auth()->check())
                        @if(auth()->user()->role === 'pelapor')
                            <a href="{{ route('pelapor.dashboard') }}" class="block px-4 py-3 rounded-lg border-2 {{ request()->routeIs('pelapor.dashboard') ? 'bg-telkom-red text-white border-neu-border shadow-neu font-bold' : 'bg-white border-transparent hover:border-neu-border font-semibold transition-all' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('pelapor.reports.create') }}" class="block px-4 py-3 rounded-lg border-2 {{ request()->routeIs('pelapor.reports.create') ? 'bg-telkom-red text-white border-neu-border shadow-neu font-bold' : 'bg-white border-transparent hover:border-neu-border font-semibold transition-all' }}">
                                Buat Laporan
                            </a>
                        @elseif(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg border-2 {{ request()->routeIs('admin.dashboard') ? 'bg-telkom-red text-white border-neu-border shadow-neu font-bold' : 'bg-white border-transparent hover:border-neu-border font-semibold transition-all' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.gis') }}" class="block px-4 py-3 rounded-lg border-2 {{ request()->routeIs('admin.gis') ? 'bg-telkom-red text-white border-neu-border shadow-neu font-bold' : 'bg-white border-transparent hover:border-neu-border font-semibold transition-all' }}">
                                Peta GIS
                            </a>
                            <a href="{{ route('admin.saw.ranking') }}" class="block px-4 py-3 rounded-lg border-2 {{ request()->routeIs('admin.saw.ranking') ? 'bg-telkom-red text-white border-neu-border shadow-neu font-bold' : 'bg-white border-transparent hover:border-neu-border font-semibold transition-all' }}">
                                Ranking Prioritas
                            </a>
                            <a href="{{ route('admin.saw.criteria') }}" class="block px-4 py-3 rounded-lg border-2 {{ request()->routeIs('admin.saw.criteria') ? 'bg-telkom-red text-white border-neu-border shadow-neu font-bold' : 'bg-white border-transparent hover:border-neu-border font-semibold transition-all' }}">
                                Kelola Kriteria
                            </a>
                            <a href="{{ route('admin.audit') }}" class="block px-4 py-3 rounded-lg border-2 {{ request()->routeIs('admin.audit') ? 'bg-telkom-red text-white border-neu-border shadow-neu font-bold' : 'bg-white border-transparent hover:border-neu-border font-semibold transition-all' }}">
                                Audit Trail
                            </a>
                        @elseif(auth()->user()->role === 'staff')
                            <a href="{{ route('staff.dashboard') }}" class="block px-4 py-3 rounded-lg border-2 {{ request()->routeIs('staff.dashboard') ? 'bg-telkom-red text-white border-neu-border shadow-neu font-bold' : 'bg-white border-transparent hover:border-neu-border font-semibold transition-all' }}">
                                Daftar Tugas
                            </a>
                        @endif
                    @endif
                </nav>
            </div>
            
            @if(auth()->check())
            <div class="p-4 border-t-2 border-neu-border bg-white">
                <div class="mb-2 px-2 font-bold">{{ auth()->user()->name }}</div>
                <div class="mb-4 px-2 text-sm text-gray-600">Role: {{ ucfirst(auth()->user()->role) }}</div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full neu-btn-secondary text-sm">Logout</button>
                </form>
            </div>
            @endif
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-neu-bg">
            <header class="bg-white border-b-2 border-neu-border p-6 flex justify-between items-center sticky top-0 z-10">
                <h2 class="text-xl font-bold">@yield('header')</h2>
            </header>
            
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-2 border-neu-border text-green-800 p-4 rounded-lg mb-6 shadow-neu font-semibold">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border-2 border-neu-border text-red-800 p-4 rounded-lg mb-6 shadow-neu font-semibold">
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('warning'))
                    <div class="bg-yellow-100 border-2 border-neu-border text-yellow-800 p-4 rounded-lg mb-6 shadow-neu font-semibold">
                        {{ session('warning') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
        
    </div>

    @stack('scripts')
</body>
</html>
