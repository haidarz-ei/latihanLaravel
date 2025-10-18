<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold text-center mb-8">Data Mahasiswa</h1>

        {{-- Notifikasi Sukses (Hijau) --}}
        @if (session('success'))
            <div 
                x-data="{ show: true }" 
                x-show="show"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="transform -translate-y-full opacity-0"
                x-transition:enter-end="transform translate-y-0 opacity-100"
                class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded shadow-lg z-50"
                style="max-width: 90%;">

                <div class="flex items-start justify-between space-x-4">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-700 hover:text-green-900 font-bold text-lg leading-none">&times;</button>
                </div>
            </div>
        @endif

        {{-- Notifikasi Error (Merah) --}}
        @if (session('error'))
            <div 
                x-data="{ show: true }" 
                x-show="show"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="transform -translate-y-full opacity-0"
                x-transition:enter-end="transform translate-y-0 opacity-100"
                class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded shadow-lg z-50"
                style="max-width: 90%;">
                
                <div class="flex items-start justify-between space-x-4">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-700 hover:text-red-900 font-bold text-lg leading-none">&times;</button>
                </div>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-center">No</th>
                            <th class="border border-gray-300 px-4 py-2">Nama</th>
                            <th class="border border-gray-300 px-4 py-2">NIM</th>
                            <th class="border border-gray-300 px-4 py-2">Alamat</th>
                            <th class="border border-gray-300 px-4 py-2">Kelas</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $mhs)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2 text-center">{{ $loop->iteration }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $mhs->nama }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $mhs->nim }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $mhs->alamat }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $mhs->kelas->nama_kelas ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                    <a href="{{ route('mahasiswa.edit', $mhs->id) }}" class="inline-block px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
                                    <form action="{{ route('mahasiswa.destroy', $mhs->id) }}" method="POST" class="inline-block ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Ingin menghapus data ini?')" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="{{ route('mahasiswa.create') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tambah Mahasiswa</a>
            </div>
        </div>
    </div>
</body>
</html>
