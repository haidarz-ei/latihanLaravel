<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Footer Links') }}
        </h2>
    </x-slot>

    <div x-data="footerPage()" class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ------------------------------------------------------------------- --}}
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
            {{-- ------------------------------------------------------------------- --}}

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-16">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Footer Settings</h3>
                    <form method="POST" action="{{ route('admin.landing.footer.brand.save') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block mb-1 font-medium">Judul Footer (Brand Title)</label>
                            <input type="text"
                                name="footer_brand_title"
                                value="{{ old('footer_brand_title', $footerBrand['footer_brand_title'] ?? '') }}"
                                class="w-full border rounded px-3 py-2"
                                placeholder="Contoh: LP3I">
                        </div>

                        <div>
                            <label class="block mb-1 font-medium">Deskripsi Footer (Brand Description)</label>
                            <textarea name="footer_brand_description"
                                    rows="3"
                                    class="w-full border rounded px-3 py-2"
                                    placeholder="Deskripsi singkat tentang institusi">{{ old('footer_brand_description', $footerBrand['footer_brand_description'] ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="block mb-1 font-medium">Footer Text (Copyright/Info)</label>
                            <input type="text"
                                name="footer_text"
                                value="{{ old('footer_text', $footerBrand['footer_text'] ?? '') }}"
                                class="w-full border rounded px-3 py-2"
                                placeholder="Contoh: © 2025 LP3I College - All Rights Reserved">
                            <p class="text-xs text-gray-500 mt-1">Teks yang muncul di bagian bawah footer</p>
                        </div>

                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Simpan Footer Settings
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tombol Tambah --}}
            <div class="mb-6">
                <button @click="openCreateModal()"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Tambah Link
                </button>
            </div>

            {{-- Tabel Data --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="font-semibold text-lg mb-4">Daftar Footer Links</h3>

                    <table class="table-auto w-full border">
                        <thead class="bg-gray-200 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-center w-16">Pos</th>
                                <th class="px-4 py-2">Label</th>
                                <th class="px-4 py-2">URL</th>
                                <th class="px-4 py-2 text-center w-24">Status</th>
                                <th class="px-4 py-2 text-center w-40">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($links as $item)
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
                                            {{-- EDIT BUTTON --}}
                                            <button type="button"
                                                    data-item='@json($item)'
                                                    @click="openEditModal(JSON.parse($el.dataset.item))"
                                                    class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                Edit
                                            </button>

                                            {{-- DELETE --}}
                                            <form action="{{ route('admin.landing.footer.destroy', $item->id) }}"
                                                  method="POST" 
                                                  class="inline-block"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus link ini?')">
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

        {{-- MODAL CREATE --}}
        <div x-show="showCreate"
             class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
             x-transition>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-96 shadow-lg" @click.stop>
                <h2 class="text-xl font-semibold mb-4">Tambah Footer Link</h2>

                <form method="POST" action="{{ route('admin.landing.footer.store') }}" class="space-y-4">
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

        {{-- MODAL EDIT --}}
        <div x-show="showEdit"
             class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
             x-transition>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-96 shadow-lg" @click.stop>
                <h2 class="text-xl font-semibold mb-4">Edit Footer Link</h2>

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

{{-- 
Di Blade template ini, kita ingin memberi tahu pengguna jika ada kesalahan saat mereka mengisi form. 
Pertama, kode @if($errors->any()) mengecek, “Apakah ada error validasi dari server?” Jika ada, maka masuk ke blok berikutnya. 
Di sini, kita membuat sebuah kotak <div> yang tampil mencolok dengan latar merah muda dan teks merah agar user langsung tahu ada masalah. 
Lalu, semua error yang terjadi diambil satu per satu lewat @foreach($errors->all() as $error) dan ditampilkan sebagai daftar <li> di dalam <ul>. 
Jadi, ketika pengguna salah mengisi form—misalnya tidak mengisi kolom wajib atau formatnya salah—mereka akan langsung melihat daftar kesalahan itu di atas form, sehingga bisa memperbaikinya sebelum submit lagi.
--}}


                <form method="POST"
                      :action="'/admin/landing/footer/' + editData.id"
                      class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-1 font-medium">Label</label>
                        <input type="text"
                               name="label"
                               x-model="editData.label"
                               class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">URL</label>
                        <input type="text"
                               name="url"
                               x-model="editData.url"
                               class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Position</label>
                        <input type="number" name="position" x-model="editData.position"
                            class="w-full border rounded px-3 py-2" 
                            min="1"
                            required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Status</label>
                        <select name="status"
                                x-model.number="editData.status"
                                class="w-full border rounded px-3 py-2" required>
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

    {{-- ALPINE.JS CONTROLLER --}}
    <script>
        function footerPage() {
            return {
                showCreate: false,
                showEdit: false,
                editData: {},

                openCreateModal() {
                    this.showCreate = true;
                },

                openEditModal(item) {
                    try {
                        console.log('Edit modal opened with item:', item);
                        this.editData = {
                            id: item.id || null,
                            label: item.label || '',
                            url: item.url || '',
                            status: item.status ? Number(item.status) : 1,
                            position: item.position || 1,
                        };
                        this.showEdit = true;
                    } catch (error) {
                        console.error('Error opening edit modal:', error);
                        alert('Terjadi kesalahan saat membuka form edit. Silakan refresh halaman.');
                    }
                }
            };
        }
    </script>

    {{-- 
    Laporan Singkat Halaman Pengelolaan Footer
    
    Halaman ini menyediakan interface lengkap untuk mengelola footer landing page dengan dua bagian utama: 
    Footer Settings (brand title, description, dan footer text) dan Footer Links (daftar link navigasi). 
    Sistem menggunakan pendekatan modal-based dengan Alpine.js untuk operasi CRUD yang efisien.
    
    Fitur Utama:
    - Footer Settings: Form terintegrasi untuk mengelola tiga setting footer yang disimpan di LandingSetting:
      * footer_brand_title: Judul brand/institusi di footer
      * footer_brand_description: Deskripsi singkat tentang institusi
      * footer_text: Teks copyright atau informasi tambahan di bagian bawah footer
    - Tambah Link: Modal create dengan position otomatis jika dikosongkan, memungkinkan penambahan link 
      di posisi terakhir secara otomatis.
    - Edit Link: Modal edit dengan pergeseran posisi otomatis saat position diubah, memastikan urutan 
      link tetap konsisten tanpa gap. Menggunakan data attribute untuk menghindari syntax error pada 
      karakter khusus.
    - Hapus Link: Konfirmasi sebelum hapus dan pergeseran posisi otomatis untuk link yang tersisa.
    - Pengelolaan Posisi: Sistem otomatis menggeser posisi link lain saat ada perubahan, menggunakan 
      DB transaction untuk konsistensi.
    
    Integrasi dengan Settings:
    - Footer settings (brand_title, brand_description, footer_text) terintegrasi dengan LandingSetting.
    - Data dapat di-edit baik dari halaman Footer maupun Settings tanpa duplikasi.
    - Menggunakan updateOrCreate untuk memastikan data selalu tersedia.
    
    Teknologi:
    - Alpine.js untuk interaktivitas modal dan data binding.
    - Blade template dengan integrasi LandingSetting untuk footer settings.
    - DB transaction untuk konsistensi urutan posisi.
    - Data attribute untuk safe JSON parsing (menghindari syntax error).
    - Flash messages dengan Alpine.js untuk notifikasi.
    
    Keunggulan:
    - Tiga setting footer (title, description, text) dapat dikelola dari satu halaman.
    - Pengelolaan posisi otomatis tanpa gap.
    - Interface yang konsisten dengan bagian lain dari admin panel.
    - Validasi lengkap untuk memastikan data valid.
    - Tidak ada duplikasi data, semua tersimpan di LandingSetting.
    --}}

</x-app-layout>
