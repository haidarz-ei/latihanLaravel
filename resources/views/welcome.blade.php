<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $landing['site_title'] ?? 'LP3I - Kampus Vokasi Terbaik' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 font-sans">

{{-- ================= NAVBAR ================= --}}
<header class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center py-4 px-6">

        {{-- Logo --}}
        <img
            src="{{ !empty($landing['site_logo'])
                ? asset('storage/'.$landing['site_logo'])
                : asset('uploads/landing/logoLp3i.jpg') }}"
            class="h-10 w-auto object-contain"
        >

        {{-- Navigation --}}
        <nav class="hidden md:flex gap-8 text-gray-700 font-medium">
            @foreach ($navigation as $nav)
                <a href="{{ $nav->url }}" class="hover:text-blue-600">
                    {{ $nav->label }}
                </a>
            @endforeach
        </nav>

        {{-- Auth --}}
        <div class="hidden md:flex gap-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="px-4 py-2 border border-blue-600 text-blue-600 rounded">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 border border-blue-600 text-blue-600 rounded">
                    Login
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded">
                        Register
                    </a>
                @endif
            @endauth
        </div>
    </div>
</header>

{{-- ================= HERO ================= --}}
<section id="beranda" class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-6 flex flex-col-reverse md:flex-row items-center gap-8">

        <div class="md:w-1/2 space-y-6">
            <h2 class="text-4xl md:text-5xl font-bold">
                {!! $landing['hero_title']
                    ?? 'Kampus Vokasi Terbaik di Indonesia<br>Untuk Masa Depan Gemilang' !!}
            </h2>

            <p class="text-gray-600 text-lg">
                {!! $landing['hero_subtitle']
                    ?? 'LP3I hadir dengan fokus pendidikan vokasi yang relevan dengan dunia kerja.' !!}
            </p>

            <div class="space-x-4">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="px-6 py-3 bg-blue-600 text-white rounded shadow">
                        {{ $landing['hero_cta_primary'] ?? 'Daftar Sekarang' }}
                    </a>
                @endif

                <a href="#program"
                   class="px-6 py-3 border border-blue-600 text-blue-600 rounded">
                    {{ $landing['hero_cta_secondary'] ?? 'Lihat Program' }}
                </a>
            </div>
        </div>

        <div class="md:w-1/2 flex justify-center">
            <img
                src="{{ asset('storage/' . ($landing['hero_image'] ?? 'landing/default-hero.png')) }}"
                class="w-full max-w-2xl rounded-xl shadow-lg"
            >
        </div>
    </div>
</section>

{{-- ================= PROGRAM ================= --}}
<section id="program" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 text-center">

        <h3 class="text-3xl font-bold mb-12">
            {{ $landing['program_title'] ?? 'Program Pendidikan' }}
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($programs as $program)
                <div class="p-6 border rounded-lg shadow hover:shadow-lg">

                    @if($program->image)
                        <img src="{{ asset('storage/'.str_replace('\\', '/', $program->image)) }}"
                             alt="{{ $program->title }}"
                             class="h-16 mx-auto mb-4 rounded object-cover">
                    @elseif($program->icon)
                        <div class="text-4xl mb-4 text-center">{!! $program->icon !!}</div>
                    @endif

                    <h4 class="text-xl font-semibold mb-2">
                        {{ $program->title }}
                    </h4>

                    <p class="text-gray-600">
                        {{ $program->description }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= ABOUT ================= --}}
<section id="tentang" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center gap-8">

        <div class="md:w-1/2 space-y-4">
            <h3 class="text-3xl font-bold">
                {{ $about->title ?? 'Tentang LP3I' }}
            </h3>

            <p class="text-gray-600">
                {{ $about->paragraph_1 ?? 'LP3I adalah lembaga pendidikan vokasi yang telah berdiri lebih dari 30 tahun, berfokus pada pendidikan yang langsung terhubung dengan dunia kerja.' }}
            </p>

            <p class="text-gray-600">
                {{ $about->paragraph_2 ?? 'Dengan kurikulum berbasis industri, dosen praktisi, dan jaringan perusahaan luas, LP3I telah membantu ribuan lulusan untuk siap bekerja sejak semester awal.' }}
            </p>
        </div>

        <div class="md:w-1/2 flex justify-center">
            <img
                src="{{ asset('storage/' . ($about->image ?? 'landing/default-hero.png')) }}"
                class="w-full max-w-2xl rounded-xl shadow-lg"
            >
        </div>
    </div>
</section>

{{-- ================= FOOTER ================= --}}
<footer id="kontak" class="bg-blue-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">

        <div>
            <h4 class="font-bold text-lg">
                {{ $landing['footer_brand_title'] ?? 'LP3I' }}
            </h4>
            <p>
                {{ $landing['footer_brand_description'] ?? 'Kampus vokasi mempersiapkan mahasiswa siap kerja lebih cepat.' }}
            </p>
        </div>

        <div>
            <h4 class="font-bold text-lg mb-2">Navigasi</h4>
            <ul class="space-y-1">
                @foreach ($footerNav as $item)
                    <li>
                        <a href="{{ $item->url }}" class="hover:underline">
                            {{ $item->label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h4 class="font-bold text-lg mb-2">Hubungi Kami</h4>
            <p>{{ $landing['footer_email'] ?? 'info@lp3i.ac.id' }}</p>
            <p>{{ $landing['footer_phone'] ?? '(021) 12345678' }}</p>
        </div>
    </div>

    <div class="text-center">
        {{ $landing['footer_text'] ?? '© 2025 LP3I. All rights reserved' }}
    </div>
</footer>

</body>
</html>
