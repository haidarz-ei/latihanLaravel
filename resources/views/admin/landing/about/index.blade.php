<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('About Page') }}
        </h2>
    </x-slot>

    <div x-data="aboutPage()" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash & Validation Alerts --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity
                     @click.away="show=false"
                     class="mb-4 p-4 bg-green-100 text-green-700 rounded flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button @click="show=false" class="ml-4 font-bold">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" x-transition.opacity
                     @click.away="show=false"
                     class="mb-4 p-4 bg-red-100 text-red-700 rounded flex justify-between items-start">
                    <div>
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="list-disc ml-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button @click="show=false" class="ml-4 font-bold text-lg">&times;</button>
                </div>
            @endif

            <div class="mb-6">
                <button @click="openCreateModal()"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Tambah About
                </button>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Daftar About</h3>

                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">Judul</th>
                                <th class="px-4 py-2 border">Paragraf 1</th>
                                <th class="px-4 py-2 border">Paragraf 2</th>
                                <th class="px-4 py-2 border">Gambar</th>
                                <th class="px-4 py-2 border text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($abouts as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="border px-4 py-2">{{ $item->title }}</td>
                                    <td class="border px-4 py-2">{{ $item->paragraph_1 }}</td>
                                    <td class="border px-4 py-2">{{ $item->paragraph_2 }}</td>
                                    <td class="border px-4 py-2">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" 
                                                alt="{{ $item->title }}" 
                                                class="h-12 w-auto object-cover rounded">
                                        @else
                                            -
                                        @endif
                                    </td>


                                    <td class="border px-4 py-2 text-center">
                                        <button @click='openEditModal(@json($item))'
                                                class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-1">
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.landing.about.destroy', $item->id) }}"
                                            method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Hapus data ini?')"
                                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <!-- MODAL CREATE -->
        <div x-show="showCreate" x-transition
             class="fixed inset-0 bg-black/50 flex items-center justify-center p-4">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Tambah About</h2>

                <form method="POST" action="{{ route('admin.landing.about.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block mb-1 font-medium">Judul</label>
                        <input type="text" name="title" class="border-gray-300 rounded-md w-full" required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Paragraf 1</label>
                        <textarea name="paragraph_1" rows="3" class="border-gray-300 rounded-md w-full" required></textarea>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Paragraf 2</label>
                        <textarea name="paragraph_2" rows="3" class="border-gray-300 rounded-md w-full" required></textarea>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Gambar</label>
                        <input type="file" name="image" class="border-gray-300 rounded-md w-full">
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showCreate = false"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT -->
        <div x-show="showEdit" x-transition
             class="fixed inset-0 bg-black/50 flex items-center justify-center p-4">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Edit About</h2>

                <form method="POST" :action="'/admin/landing/about/' + editData.id" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-1 font-medium">Judul</label>
                        <input type="text" name="title" x-model="editData.title" class="border-gray-300 rounded-md w-full" required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Paragraf 1</label>
                        <textarea name="paragraph_1" x-model="editData.paragraph_1" rows="3" class="border-gray-300 rounded-md w-full" required></textarea>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Paragraf 2</label>
                        <textarea name="paragraph_2" x-model="editData.paragraph_2" rows="3" class="border-gray-300 rounded-md w-full" required></textarea>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Gambar</label>
                        <input type="file" name="image" class="border-gray-300 rounded-md w-full">
                        
                        <template x-if="editData.image">
                            <img :src="'/storage/' + editData.image" class="w-32 h-32 object-cover rounded mt-2">
                        </template>
                    </div>


                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showEdit = false" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Update</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function aboutPage() {
            return {
                showCreate: false,
                showEdit: false,
                editData: {},

                openCreateModal() {
                    this.showCreate = true;
                },
                openEditModal(item) {
                    this.editData = {
                        id: item.id,
                        title: item.title,
                        paragraph_1: item.paragraph_1,
                        paragraph_2: item.paragraph_2,
                        image: item.image
                    };
                    this.showEdit = true;
                }
            }
        }
    </script>

    {{-- 
    Laporan Singkat Halaman Pengelolaan About
    
    Halaman ini menyediakan interface untuk mengelola konten "Tentang" landing page dengan pendekatan modal-based 
    menggunakan Alpine.js. Sistem mendukung pengelolaan multiple about entries dengan gambar, memungkinkan admin 
    untuk menampilkan berbagai informasi tentang institusi atau organisasi.
    
    Fitur Utama:
    - Tambah About: Modal create dengan form lengkap (title, 2 paragraf, image) dan validasi semua field wajib.
    - Edit About: Modal edit dengan preview image saat ini, memungkinkan update semua field termasuk upload 
      gambar baru dengan penghapusan otomatis gambar lama.
    - Hapus About: Konfirmasi sebelum hapus dan penghapusan file image dari storage.
    - Pengelolaan File: Penghapusan otomatis file image lama saat upload file baru atau hapus data.
    
    Teknologi:
    - Alpine.js untuk interaktivitas modal dan data binding.
    - Blade template dengan validasi frontend dan backend.
    - Storage management untuk file image dengan cleanup otomatis.
    - Flash messages dengan Alpine.js untuk notifikasi yang dapat ditutup.
    
    Keunggulan:
    - Interface sederhana dan mudah digunakan.
    - Preview image saat edit untuk memudahkan admin melihat konten saat ini.
    - Cleanup file otomatis untuk menghemat storage.
    - Validasi lengkap untuk memastikan data konsisten.
    --}}

</x-app-layout>
