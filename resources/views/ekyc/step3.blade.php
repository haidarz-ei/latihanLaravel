<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Step 3 Data Pendidikan & Upload Dokumen
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-12 bg-white p-6 rounded-lg shadow-lg">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('ekyc.step3.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Asal SD --}}
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Asal Sekolah SD</label>
                <input type="text" name="asal_sd" value="{{ old('asal_sd', $data->asal_sd ?? '') }}" 
                        class="mt-1 block w-full border-gray-300 rounded-md">
            </div>
            
            {{-- Asal SMP --}}
            <div class="mb-4">
                <label for="asal_smp" class="block text-sm font-medium mb-1">Asal SMP</label>
                <input type="text" name="asal_smp" value="{{ old('asal_smp', $data->asal_smp ?? '') }}" 
                        class="mt-1 block w-full border-gray-300 rounded-md">
            </div>

            {{-- Asal SMA --}}
            <div class="mb-6">
                <label for="asal_sma" class="block text-sm font-medium mb-1">Asal SMA</label>
                <input type="text" name="asal_sma" value="{{ old('asal_sma', $data->asal_sma ?? '') }}" 
                        class="mt-1 block w-full border-gray-300 rounded-md">
            </div>

            {{-- Upload KK --}}
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Upload Kartu Keluarga (KK)</label>
                <input type="file" name="file_kk" class="mt-1 block w-full border-gray-300 rounded-md">
                @if($data && $data->file_kk)    
                    <p class="text-sm mt-1 text-gray-500">file saat ini:</p>
                    <a href="{{ asset('storage/' . $data->file_kk) }}" target="_blank" 
                        class="text-blue-600 hover:underline">Lihat KK  
                    </a>
                @endif
            </div>

            {{-- Upload Ijazah --}}
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Upload Ijazah Terakhir</label>
                <input type="file" name="file_ijazah" class="mt-1 block w-full border-gray-300 rounded-md">
                @if($data && $data->file_ijazah)
                    <p class="text-sm mt-1 text-gray-500">file saat ini:</p>
                    <a href="{{ asset('storage/' . $data->file_ijazah) }}" target="_blank" 
                        class="text-blue-600 hover:underline">Lihat Ijazah
                    </a>
                @endif
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">
                    Simpan & Lanjut
                </button>
            </div>
        </form>
    </div>  
</x-app-layout>