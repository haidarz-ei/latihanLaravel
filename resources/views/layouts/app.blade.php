<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="min-h-screen">

    {{-- ====== HEADER / NAVBAR ATAS ====== --}}
    @include('layouts.navigation')
    {{-- navigation.blade.php bawaan Breeze/Jetstream --}}

    {{-- ====== BODY: Sidebar + Konten ====== --}}
    <div class="flex">
        {{-- SIDEBAR di kiri, tepat di bawah header --}}
        <aside class="w-64 bg-white border-r shadow-sm min-h-screen">
            <nav class="p-4 space-y-2">
                @if (Auth::user() && Auth::user()->role === 'admin')
                        <div class="mb-4 text-gray-600 font-semibold">Admin Menu</div>
                    <a href="{{ route('dashboard') }}"
                        class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('dashboard') ? 'bg-gray-200 font-semibold' : '' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('mahasiswa.index') }}"
                        class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('mahasiswa.*') ? 'bg-gray-200 font-semibold' : '' }}">
                        Mahasiswa
                    </a>

                    <a href="{{ route('ruangan.index') }}"
                        class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('ruangan.*') ? 'bg-gray-200 font-semibold' : '' }}">
                        Ruangan
                    </a>

                    <a href="{{ route('matkul.index') }}"
                        class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('matkul.*') ? 'bg-gray-200 font-semibold' : '' }}">
                        Matakuliah
                    </a>

                    <a href="{{ route('dosen.index') }}"
                        class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('dosen.*') ? 'bg-gray-200 font-semibold' : '' }}">
                        Dosen
                    </a>
                @endif
            </nav>
        </aside>

        {{-- KONTEN UTAMA di kanan --}}
        <div class="flex-1">
            {{-- HEADER PAGE --}}
            @if (isset($header))
                <header class="bg-white shadow mb-4">
                    <div class="max-w-7xl mx-auto py-4 px-6">
                        {{ $header }}
                    </div>
                </header>
            @endif
            <main class="flex-1 p-6">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

</div>
</body>
</html>