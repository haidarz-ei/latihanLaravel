<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Navigation Menu') }}
        </h2>
    </x-slot>

    <div x-data="navPage()" class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <!-- {{-- Flash Message --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif -->

            {{-- Flash & Validation Alerts --}}
            <div>
                {{-- Success --}}
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition.opacity
                        @click.away="show=false"
                        class="mb-4 p-4 bg-green-100 text-green-700 rounded flex justify-between items-center">
                        <span>{{ session('success') }}</span>
                        <button @click="show=false" class="ml-4 font-bold">&times;</button>
                    </div>
                @endif

                {{-- Validation Errors --}}
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
            </div>

            {{-- Tombol Tambah --}}
            <div class="mb-6">
                <button @click="openCreateModal()"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Tambah Menu
                </button>
            </div>

            {{-- Tabel Data --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h3 class="font-semibold text-lg mb-4 text-gray-800 dark:text-gray-100">
                        Daftar Navigation Menu
                    </h3>

                    <table class="table-auto w-full border">
                        <thead class="bg-gray-200 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 w-16 text-center">Pos</th>
                                <th class="px-4 py-2">Label</th>
                                <th class="px-4 py-2">URL</th>
                                <th class="px-4 py-2 text-center w-24">Status</th>
                                <th class="px-4 py-2 text-center w-40">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td class="border px-4 py-2 text-center">{{ $item->position }}</td>
                                    <td class="border px-4 py-2">{{ $item->label }}</td>
                                    <td class="border px-4 py-2">{{ $item->url }}</td>

                                    <td class="border px-4 py-2 text-center">
                                        <span class="px-2 py-1 rounded text-white {{ $item->status ? 'bg-green-600' : 'bg-red-600' }}">
                                            {{ $item->status ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>

                                    <td class="border px-4 py-2 text-center">

                                        <div class="flex gap-2 justify-center">
                                            {{-- EDIT --}}
                                            <button @click='openEditModal(@json($item))'
                                                    class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                Edit
                                            </button>

                                            {{-- DELETE --}}
                                            <form action="{{ route('admin.landing.nav.destroy', $item->id) }}"
                                                  method="POST" 
                                                  class="inline-block"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>



        {{-- ---------------------------------------------------- --}}
        {{-- MODAL CREATE --}}
        {{-- ---------------------------------------------------- --}}
        <div x-show="showCreate"
             class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
             x-transition>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-96 shadow-lg">

                <h2 class="text-xl font-semibold mb-4">Tambah Menu Navigasi</h2>

                <form method="POST" action="{{ route('admin.landing.nav.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block mb-1 font-medium">Label</label>
                        <input type="text" name="label" 
                               class="w-full border rounded px-3 py-2" 
                               placeholder="Contoh: Beranda"
                               required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">URL</label>
                        <input type="text" name="url" 
                               class="w-full border rounded px-3 py-2" 
                               placeholder="Contoh: / atau /about"
                               required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Position</label>
                        <input type="number" name="position" 
                               class="w-full border rounded px-3 py-2" 
                               placeholder="Kosongkan untuk posisi terakhir"
                               min="1">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menambahkan di posisi terakhir</p>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Status</label>
                        <select name="status" class="w-full border rounded px-3 py-2" required>
                            <option value="1" selected>Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button"
                                @click="showCreate=false"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Batal
                        </button>

                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>



        {{-- ---------------------------------------------------- --}}
        {{-- MODAL EDIT --}}
        {{-- ---------------------------------------------------- --}}
        <div x-show="showEdit"
             class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
             x-transition>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-96 shadow-lg">

                <h2 class="text-xl font-semibold mb-4">Edit Menu</h2>

                <form method="POST"
                      :action="'/admin/landing/nav/' + editData.id"
                      class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-1 font-medium">Label</label>
                        <input type="text"
                               name="label"
                               x-model="editData.label"
                               class="w-full border rounded px-3 py-2"
                               required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">URL</label>
                        <input type="text"
                               name="url"
                               x-model="editData.url"
                               class="w-full border rounded px-3 py-2"
                               required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Position</label>
                        <input type="number"
                               name="position"
                               x-model="editData.position"
                               class="w-full border rounded px-3 py-2"
                               min="1"
                               required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Status</label>
                        <select name="status"
                                x-model="editData.status"
                                class="w-full border rounded px-3 py-2"
                                required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button"
                                @click="showEdit=false"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Batal
                        </button>

                        <button type="submit"
                                class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>




    </div>

    {{-- Alpine Controller --}}
    <script>
        function navPage() {
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
                        label: item.label,
                        url: item.url,
                        position: item.position,
                        status: item.status,
                    };
                    this.showEdit = true;
                }
            }
        }
    </script>

    {{-- 
    Laporan Singkat Halaman Pengelolaan Menu Navigasi
    
    Halaman ini menyediakan antarmuka lengkap untuk mengelola menu navigasi landing page dengan pendekatan 
    single-page application menggunakan Alpine.js. Semua operasi CRUD dilakukan melalui modal tanpa perlu 
    navigasi ke halaman terpisah, sehingga proses pengelolaan menu menjadi lebih cepat dan efisien.
    
    Fitur Utama:
    - Tambah Menu: Modal create dengan form lengkap (label, URL, position, status). Position dapat dikosongkan 
      untuk menambahkan menu di posisi terakhir secara otomatis.
    - Edit Menu: Modal edit yang terintegrasi dengan Alpine.js untuk binding data dua arah, memungkinkan 
      perubahan label, URL, position, dan status dengan mudah.
    - Hapus Menu: Form delete dengan konfirmasi JavaScript untuk mencegah penghapusan tidak sengaja.
    - Pengelolaan Posisi: Sistem otomatis menggeser posisi menu lain saat ada perubahan, memastikan urutan 
      menu tetap konsisten tanpa gap.
    
    Teknologi:
    - Alpine.js untuk interaktivitas modal dan data binding.
    - Blade template dengan komponen Laravel untuk struktur layout.
    - Validasi di frontend dan backend untuk keamanan data.
    - Flash messages dengan Alpine.js untuk notifikasi yang dapat ditutup.
    
    Keunggulan:
    - User experience yang lebih baik dengan modal interaktif.
    - Konsistensi posisi menu terjaga otomatis.
    - Validasi ganda (frontend & backend) untuk keamanan.
    - Interface yang responsif dan mudah digunakan.
    --}}

</x-app-layout>
