<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Dosen
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">

                <form method="POST" action="{{ route('dosen.update', $dosen->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700">Nama Dosen</label>
                        <input type="text" name="namaDosen" value="{{ old('namaDosen', $dosen->namaDosen) }}" class="border rounded w-full px-3 py-2">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700">NID</label>
                        <input type="text" name="nid" value="{{ old('nid', $dosen->nid) }}" class="border rounded w-full px-3 py-2">
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                        Update
                    </button>
                </form>

            </div>
        </div>
    </div>
    
</x-app-layout>
