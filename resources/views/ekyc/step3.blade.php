<x-app-layout>
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow mt-8">
        <h2 class="text-xl font-semibold mb-4 text-center">
            Step 3 - Data Pendidikan & Dokumen Keluarga
        </h2>

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

            {{-- Tombol Navigasi --}}
            <div class="flex justify-between items-center mt-4">
                <a href="{{ route('ekyc.step2') }}" class="mr-4 inline-block bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 transition duration-200">
                    Back
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Simpan & Lanjut Step 4
                </button>
            </div>

        </form>
    </div>  
</x-app-layout>