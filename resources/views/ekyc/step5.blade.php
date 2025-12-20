<x-app-layout>
    <div class="min-h-screen flex flex-col items-center justify-start bg-gray-50 px-4 pt-12">
        <h2 class="text-2xl font-semibold text-blue-700 mb-4">
            Status eKYC Anda
        </h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded-md mb-3 max-w-md">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded-md mb-3 max-w-md">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-xl p-6 max-w-md w-full text-center">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-16 w-16 text-blue-500 mb-4 mx-auto" 
                 fill="none" 
                 viewBox="0 0 24 24"
                 stroke="currentColor" 
                 stroke-width="2">
                <path stroke-linecap="round" 
                      stroke-linejoin="round"
                      d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z" />
            </svg>

            <p class="text-gray-700 mb-3 font-medium">
                Terima kasih telah menyelesaikan proses registrasi <strong>eKYC</strong>.
            </p>
            
            <p class="text-gray-600 mb-4">
                Data Anda telah kami terima dan sedang dalam proses verifikasi oleh tim kami.
            </p>
            
            <p class="text-gray-600 mb-6">
                Mohon menunggu maksimal <strong>1x24 jam</strong> untuk hasil verifikasi.
            </p>

            {{-- Informasi eKYC yang sudah di-submit --}}
            @if($data)
                <div class="bg-gray-50 rounded-lg p-4 mb-4 text-left">
                    <h3 class="font-semibold text-gray-800 mb-2">Data yang Dikirim:</h3>
                    <div class="space-y-1 text-sm text-gray-600">
                        <p><strong>Nama:</strong> {{ $data->nama ?? '-' }}</p>
                        <p><strong>NIK:</strong> {{ $data->nik ?? '-' }}</p>
                        <p><strong>Status:</strong> 
                            <span class="px-2 py-1 rounded text-white bg-yellow-600">
                                Menunggu Verifikasi
                            </span>
                        </p>
                    </div>
                </div>
            @endif

            <a href="{{ route('ekyc.step5') }}"
               class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition inline-block">
                🔄 Cek Lagi
            </a>
        </div>
    </div>
</x-app-layout>
