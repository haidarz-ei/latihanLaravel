<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- konten : title -->
    <title>LP3I - Kampus Vokasi Terbaik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 font-sans">

    <!-- Navbar -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center py-4 px-6">
            <!-- Logo -->
            <h1>
                <img src="{{ asset('uploads/landing/logoLp3i.jpg') }}" 
                    alt="LP3I Logo" 
                    class="h-10 w-auto object-contain">
            </h1>

            <!-- Navigation Links -->
            <nav class="hidden md:flex gap-8 text-gray-700 font-medium">
                @foreach ($navigation as $nav)
                    <a href="{{ $nav->url }}" class="hover:text-blue-600">
                        {{ $nav->label }}
                    </a>
                @endforeach
            </nav>
            <div class="space-x-4 hidden md:flex">
                @if (Route::has('login'))
                    <nav class="flex items-center justify-end gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 border border-blue-600 text-blue-600 rounded hover:bg-blue-50">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 border border-blue-600 text-blue-600 rounded hover:bg-blue-50">Login</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Register</a>
                            @endif
                        @endauth
                    </nav>
                @endif

                <!-- <a href="#login" class="px-4 py-2 border border-blue-600 text-blue-600 rounded hover:bg-blue-50">Login</a> -->
                <!-- <a href="#daftar" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Daftar</a> -->
            </div>
        </div>
    </header>

    <!-- hero section -->
    <section id="beranda" class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-6 flex flex-col-reverse md:flex-row items-center gap-8">
            <!-- Text Content -->
             <!-- konten : banner wording -->
            <div class="md:w-1/2 space-y-6">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800">
                    {{ $landing['hero_title'] ?? 'Kampus Vokasi Terbaik di Indonesia <br/> Untuk Masa Depan Gemilang' }}
                </h2>
                    <p class="text-gray-600 text-lg">
                        {!! $landing['hero_subtitle'] ?? 'LP3I hadir dengan fokus pendidikan vokasi yang relevan dengan dunia kerja.
                        Raih keterampilan praktis dan peluang karier lebih cepat bersama kami' !!}
                    </p>
                    <div class="space-x-4">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-3 bg-blue-600 text-white rounded shadow hover:bg-blue-700">👆 Daftar Sekarang</a>
                        @endif
                        <a href="#program" class="px-6 py-3 border border-blue-600 text-blue-600 rounded hover:bg-blue-50">👀 Lihat Program</a>
                    </div>
                </div>
                
                <!-- Image Content -->
                <div class="md:w-1/2 flex justify-center">
                    <img src="{{ asset('uploads/' . ($landing['hero_image'] ?? 'default-hero.png')) }}" alt="Mahasiswa LP3I" class="w-full max-w-2xl object-cover object-cover rounded-xl shadow-lg" />
                </div>
            </div>
        </section>

    <!-- program pendidikan -->
    <section id="program" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h3 class="text-3xl font-bold mb-12">Program Pendidikan</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($programs as $program)
                    <div class="p-6 border rounded-lg shadow hover:shadow-lg transition">
                        <h4 class="text-xl font-semibold mb-2">{{  $program->name }}</h4>
                        <p class="text-gray-600">{{ $program->description }}</p>
                    </div>
                @endforeach
                <!-- 
                <div class="p-6 border rounded-lg shadow hover:shadow-lg transition">
                    <h4 class="text-xl font-semibold mb-2">Administrasi Bisnis</h4>
                    <p class="text-gray-600">Belajar pengelolaan bisnis, administrasi perkantoran, dan dunia manajemen modern.</p>
                </div>
                <div class="p-6 border rounded-lg shadow hover:shadow-lg transition">
                    <h4 class="text-xl font-semibold mb-2">Informatika & Komputer</h4>
                    <p class="text-gray-600">Program vokasi untuk dunia IT: pemrograman, jaringan, dan data.</p>
                </div>
                <div class="p-6 border rounded-lg shadow hover:shadow-lg transition">
                    <h4 class="text-xl font-semibold mb-2">Digital Marketing</h4>
                    <p class="text-gray-600">Menguasai strategi pemasaran digital kebutuhan industri.</p>
                </div> 
                -->
            </div>
        </div>
    </section>

    <!-- tentang LP3I -->
<section id="tentang" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center gap-8">
            <div class="md:w-1/2 space-y-4">
                <h3 class="text-3xl font-bold mb-4">Tentang LP3I</h3>
                <p class="text-gray-600">LP3I adalah lembaga pendidikan vokasi yang telah berdiri lebih dari 30 tahun, berfokus pada pendidikan yang langsung terhubung dengan dunia kerja.</p>
                <p class="text-gray-600">Dengan kurikulum berbasis industri, dosen praktisi, dan jaringan perusahaan luas, LP3I telah membantu ribuan lulusan untuk siap bekerja sejak semester awal.</p>
            </div>
            <div class="md:w-1/2">
                <img src="{{ asset('uploads/landing/mahasiswa-lp3i.png') }}" class="rounded-xl shadow-lg" />
            </div>
        </div>
    </section>

    <!-- footer -->
    <footer id="kontak" class="bg-blue-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <div>
                <h4 class="font-bold text-lg mb-2">LP3I</h4>
                <p>Kampus vokasi mempersiapkan mahasiswa siap kerja lebih cepat.</p>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-2">Navigasi</h4>
                <ul class="space-y-1">
                    @foreach ($footerNav as $itemNav)
                        <li><a href="{{ $itemNav->url }}" class="hover:underline">
                                {{ $itemNav->label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-2">Hubungi Kami</h4>
                <p>Email: info@lp3i.ac.id</p>
                <p>Telepon: (021) 12345678</p>
            </div>
        </div>
        <div class="text-center text-white">
            {{ $landing['footer_text'] ?? '© 2025 LP3I. All rights reserved' }} 
        </div>
    </footer>

</body>
</html>
