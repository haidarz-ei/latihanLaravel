<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit MataKuliah
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">

                <form method="POST" action="{{ route('matkul.update', $matkul->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700">Nama MataKuliah</label>
                        <input type="text" name="namaMatkul" value="{{ old('namaMatkul', $matkul->namaMatkul) }}" class="border rounded w-full px-3 py-2">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700">Deskripsi</label>
                        <input type="text" name="deskripsi" value="{{ old('deskripsi', $matkul->deskripsi) }}" class="border rounded w-full px-3 py-2">
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                        Update
                    </button>
                </form>

            </div>
        </div>
    </div>
    
</x-app-layout>
