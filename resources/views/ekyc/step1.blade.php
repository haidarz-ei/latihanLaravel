<x-app-layout>
    <div class="max-w-2xl mx-auto mt-12 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold mb-4 text-center">
            Step 1 - Personal Information
        </h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="text-sm list-disc ml-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ekyc.storeStep1') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="full_name" class="block text-sm font-medium mb-1">Full Name</label>
                <input type="text" name="nama"
                        value="{{ old('nama', $ekyc->nama ?? '') }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" required/>
            </div>

            <div class="mb-6">
                <label for="nik" class="block text-sm font-medium mb-1">NIK</label>
                <input type="text" name="nik"
                        value="{{ old('nik', $ekyc->nik ?? '') }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" required/>
            </div>

            <div class="mb-4">
                <label for="date_of_birth" class="block text-sm font-medium mb-1">Date of Birth</label>
                <input type="date" name="tanggal_lahir"
                        value="{{ old('tanggal_lahir', $ekyc->tanggal_lahir ?? '') }}"
                        class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" required />
            </div>

            <div class="mb-4">
                <label for="address" class="block text-sm font-medium mb-1">Address</label>
                <textarea name="alamat" rows="3" 
                    class="w-full border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200" 
                    required>{{ old('alamat', $ekyc->alamat ?? '') }}
                </textarea>
            </div>


            {{-- Tombol Navigasi --}}
            <div class="flex justify-end mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Simpan & Lanjut Step 2
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
