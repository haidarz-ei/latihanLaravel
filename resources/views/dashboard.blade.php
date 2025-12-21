<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if(auth()->user()->role === 'admin')
        {{-- ==================== ADMIN DASHBOARD ==================== --}}
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Statistik Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    {{-- Total Mahasiswa --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-blue-500">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Mahasiswa</p>
                                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_mahasiswa'] }}</p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- eKYC Pending --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-yellow-500">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">eKYC Pending</p>
                                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['ekyc_pending'] }}</p>
                                </div>
                                <div class="bg-yellow-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            @if($stats['ekyc_pending'] > 0)
                                <a href="{{ route('admin.ekyc.index') }}" class="text-xs text-yellow-600 hover:underline mt-2 block">
                                    Verifikasi sekarang →
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Total Dosen --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-green-500">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Dosen</p>
                                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_dosen'] }}</p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total Program --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-purple-500">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Program Aktif</p>
                                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_program'] }}</p>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Verification Rate & Status --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        {{-- Verification Rate --}}
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Verification Rate</h3>
                                <div class="space-y-3">
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600">Tingkat Penerimaan</span>
                                            <span class="font-semibold">{{ $verification_rate }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-3">
                                            <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all duration-500" 
                                                 style="width: {{ $verification_rate }}%"></div>
                                        </div>
                                    </div>
                                    <div class="pt-2 border-t">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Diterima</span>
                                            <span class="font-semibold text-green-600">{{ $stats['ekyc_accepted'] }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm mt-1">
                                            <span class="text-gray-600">Total Verifikasi</span>
                                            <span class="font-semibold">{{ $stats['ekyc_accepted'] + $stats['ekyc_rejected'] + $stats['ekyc_pending'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- eKYC Status --}}
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status eKYC</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-600">Draft</span>
                                        </div>
                                        <span class="font-semibold">{{ $ekyc_by_status['draft'] }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-600">Menunggu</span>
                                        </div>
                                        <span class="font-semibold">{{ $ekyc_by_status['submitted'] }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-600">Diterima</span>
                                        </div>
                                        <span class="font-semibold">{{ $ekyc_by_status['accepted'] }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-600">Ditolak</span>
                                        </div>
                                        <span class="font-semibold">{{ $ekyc_by_status['rejected'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                            <div class="space-y-2">
                                <a href="{{ route('admin.ekyc.index') }}" 
                                   class="block w-full text-left px-4 py-2 bg-yellow-50 text-yellow-700 rounded hover:bg-yellow-100 transition">
                                    ⏳ Verifikasi eKYC
                                </a>
                                <a href="{{ route('mahasiswa.index') }}" 
                                   class="block w-full text-left px-4 py-2 bg-blue-50 text-blue-700 rounded hover:bg-blue-100 transition">
                                    👥 Kelola Mahasiswa
                                </a>
                                <a href="{{ route('admin.landing.settings.index') }}" 
                                   class="block w-full text-left px-4 py-2 bg-purple-50 text-purple-700 rounded hover:bg-purple-100 transition">
                                    🛠 Kelola Landing Page
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent eKYC Pending --}}
                @if($recent_ekyc->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">eKYC Pending Terbaru</h3>
                                <a href="{{ route('admin.ekyc.index') }}" class="text-sm text-blue-600 hover:underline">
                                    Lihat Semua →
                                </a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIK</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($recent_ekyc as $ekyc)
                                            <tr>
                                                <td class="px-4 py-3 text-sm text-gray-900">{{ $ekyc->nama ?? $ekyc->user->name ?? '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $ekyc->nik ?? '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $ekyc->created_at->format('d M Y') }}</td>
                                                <td class="px-4 py-3 text-sm">
                                                    <a href="{{ route('admin.ekyc.show', $ekyc->id) }}" 
                                                       class="text-blue-600 hover:underline">
                                                        Verifikasi
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @else
        {{-- ==================== USER DASHBOARD ==================== --}}
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-xl font-semibold mb-4">Selamat Datang, {{ auth()->user()->name }}!</h3>
                        
                        @if($ekyc && $ekyc->status === 'accepted')
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                <p class="text-green-800">
                                    ✅ eKYC Anda telah diterima. Selamat bergabung di LP3I!
                                </p>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-semibold mb-2">Informasi eKYC</h4>
                                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                        <p><strong>Nama:</strong> {{ $ekyc->nama ?? '-' }}</p>
                                        <p><strong>NIK:</strong> {{ $ekyc->nik ?? '-' }}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">
                                                Diterima
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif($ekyc && $ekyc->status === 'rejected')
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                <p class="text-red-800">
                                    ❌ eKYC Anda ditolak. Silakan lengkapi ulang data eKYC Anda.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>

