<x-app-layout>
    <div class="max-w-2xl mx-auto mt-12 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-center mb-6">eKYC - Step 2: Upload Dokumen</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('ekyc.step2.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="ktp" class="block text-sm font-medium mb-1">Foto KTP</label>
                <input type="file" name="file_ktp" accept="image/*" class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200"/>
                @if($data && $data->file_ktp)
                    <p class="text-sm mt-1 text-gray-500">Sudah Upload: {{ basename($data->file_ktp) }}</p>
                @endif

                @if ($data && $data->file_ktp)
                    <img src="{{ asset('storage/' . $data->file_ktp) }}" class="h-32 rounded mt-2 max-h-48 border"/>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Foto Selfie dengan KTP</label>
                <input type="file" name="file_selfie" accept="image/*" class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200"/>
                @if($data && $data->file_selfie)
                    <p class="text-sm mt-1 text-gray-500">Sudah Upload: {{ basename($data->file_selfie) }}</p>
                @endif
            </div>

            @if ($data && $data->file_selfie)
                <img src="{{ asset('storage/' . $data->file_selfie) }}" class="h-32 rounded mt-2 max-h-48 border"/>
            @endif
        

            <div class="flex justify-end mt-6">
                <a href="{{ route('ekyc.step1') }}" class="mr-4 inline-block bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 transition duration-200">
                    Back
                </a>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-200">
                    Submit Dokumen
                </button>
            </div>
        </form>
    </div>
</x-app-layout>