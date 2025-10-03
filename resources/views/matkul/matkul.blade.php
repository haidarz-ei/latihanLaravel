


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data MataKuliah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Form Tambah MataKuliah --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4">Tambah MataKuliah</h3>

                    <form method="POST" action="{{ route('matkul.store') }}" class="space-y-4">
                        @csrf
                        <input type="text" name="namaMatkul" placeholder="Nama MataKuliah" class="border-gray-300 rounded-md w-full">
                        <input type="text" name="deskripsi" placeholder="Deskripsi" class="border-gray-300 rounded-md w-full">

                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>

            {{-- List MataKuliah --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4">Daftar MataKuliah</h3>
                    <table class="table-auto w-full border">
                        <thead class="bg-gray-200 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 w-16 text-center">No</th>
                                <th class="px-4 py-2">Nama MataKuliah</th>
                                <th class="px-4 py-2">Deskripsi</th>
                                <th class="px-4 py-2 w-16 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $matkul)
                            <tr>
                                <td class="border px-4 py-2 text-center">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2">{{ $matkul->namaMatkul }}</td>
                                <td class="border px-4 py-2">{{ $matkul->deskripsi }}</td>
                                <td class="border px-4 py-2 text-center">
                                    <a href="{{ route('matkul.edit', $matkul->id) }}" 
                                    class="inline-block px-3 py-1 bg-yellow-500 text-white rounded">
                                    Edit
                                    </a>

                                    <form action="{{ route('matkul.destroy', $matkul->id) }}" 
                                        method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('ingin menghapus data ini?')"
                                            class="px-3 py-1 bg-red-600 text-white rounded">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


