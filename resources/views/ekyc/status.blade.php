<x-app-layout>
    <div class="min-h-screen flex flex-col items-center justify-start bg-gray-50 px-4 pt-12">
        <h2 class="text-2xl font-semibold {{ $status == 'accepted' ? 'text-green-700' : 'text-red-700' }} mb-4">
            eKYC {{ $status == 'accepted' ? 'Diterima ✅' : 'Ditolak ❌' }}
        </h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded-md mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded-md mb-3">
                {{ session('error') }}
            </div>
        @endif

        @if (session('info'))
            <div class="bg-blue-100 text-blue-700 px-4 py-2 rounded-md mb-3">
                {{ session('info') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-xl p-6 max-w-md text-center">
            <svg class="w-16 h-16 mx-auto {{ $status == 'accepted' ? 'text-green-600' : 'text-red-600' }} mb-4"
                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                @if ($status == 'accepted')
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                @else
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />
                @endif
            </svg>

            <p class="text-gray-700 mb-2">
                @if ($status == 'accepted')
                    Selamat! Registrasi <strong>eKYC</strong> anda telah diterima.
                @else
                    Registrasi <strong>eKYC</strong> anda ditolak.
                @endif
            </p>

            @if ($status == 'accepted')
                <p class="text-gray-600 mb-6">
                    <strong>Silahkan Masuk</strong>.
                </p>
            @endif

            @if ($status == 'accepted')
                <a href="{{ route('dashboard') }}"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition inline-block">
                    🚀 Masuk
                </a>
            @else
                <a href="{{ route('ekyc.step1') }}"
                    class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700 transition inline-block">
                    🔄 Kembali ke Step 1
                </a>
            @endif

        </div>
    </div>
</x-app-layout>
