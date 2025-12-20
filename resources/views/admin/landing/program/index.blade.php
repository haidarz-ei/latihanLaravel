<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Programs') }}
        </h2>
    </x-slot>

    <div x-data="programPage()" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- {{-- Flash --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif
            {{-- Flash & Validation Alerts --}} -->
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

            {{-- Add --}}
            <div class="mb-6">
                <button @click="openCreateModal()"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Tambah Program
                </button>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="font-semibold text-lg mb-4">Daftar Programs</h3>

                    <table class="table-auto w-full border">
                        <thead class="bg-gray-200 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-center w-20">Gambar</th>
                                <th class="px-4 py-2">Title</th>
                                <th class="px-4 py-2">Icon</th>
                                <th class="px-4 py-2 text-center w-24">Pos</th>
                                <th class="px-4 py-2 text-center w-24">Status</th>
                                <th class="px-4 py-2 text-center w-40">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($programs as $program)
                                <tr>
                                    <td class="border px-4 py-2 text-center">
                                        @if($program->image)
                                            <img src="{{ asset('storage/'.str_replace('\\', '/', $program->image)) }}" 
                                                 alt="{{ $program->title }}"
                                                 class="h-12 mx-auto rounded object-cover">
                                        @elseif($program->icon)
                                            <div class="flex justify-center">{!! $program->icon !!}</div>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">{{ $program->title }}</td>
                                    <td class="border px-4 py-2">{{ $program->icon }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $program->position }}</td>

                                    <td class="border px-4 py-2 text-center">
                                        <span class="px-2 py-1 rounded text-white {{ $program->status ? 'bg-green-600' : 'bg-red-600' }}">
                                            {{ $program->status ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>

                                    <td class="border px-4 py-2 text-center">
                                        <div class="flex gap-2 justify-center">
                                            <button type="button"
                                                    @click="openEditModal({{ $program }})"
                                                    class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                Edit
                                            </button>

                                            <form action="{{ route('admin.landing.programs.destroy', $program->id) }}"
                                                  method="POST" 
                                                  class="inline-block"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus program ini?')">
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

        {{-- CREATE MODAL --}}
        <div x-show="showCreate"
             class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
             x-transition>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-96 shadow-lg" @click.stop>

                <h2 class="text-xl font-semibold mb-4">Tambah Program</h2>

                <form method="POST" action="{{ route('admin.landing.programs.store') }}" 
                        enctype="multipart/form-data" class="space-y-4">
                    @csrf


                    <div>
                        <label class="block mb-1 font-medium">Title</label>
                        <input type="text" name="title" 
                               class="w-full border rounded px-3 py-2" 
                               placeholder="Nama program studi"
                               required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Description</label>
                        <textarea name="description" 
                                  rows="3"
                                  class="w-full border rounded px-3 py-2"
                                  placeholder="Deskripsi program studi"></textarea>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Tipe Konten</label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input type="radio" value="icon" x-model="createContentType" class="mr-2"> Icon
                            </label>
                            <label class="flex items-center">
                                <input type="radio" value="image" x-model="createContentType" class="mr-2"> Image
                            </label>
                        </div>
                    </div>

                    <input type="hidden" name="content_type" :value="createContentType">

                    <div x-show="createContentType === 'icon'">
                        <label class="block mb-1 font-medium">Icon HTML</label>
                        <input type="text" name="icon" 
                               placeholder="Contoh: <i class='fa fa-book'></i>" 
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div x-show="createContentType === 'image'">
                        <label class="block mb-1 font-medium">Upload Gambar</label>
                        <input type="file" name="image" 
                               accept="image/*"
                               class="w-full border rounded px-3 py-2">
                    </div>

<!-- Modal Tambah Program dikembangkan menjadi lebih interaktif dengan penambahan pilihan tipe konten (icon atau image). 
Field yang tampil menyesuaikan pilihan, dan input tersembunyi content_type memastikan data dikirim sesuai tipe. 
Perubahan ini membuat form lebih user-friendly, mencegah kesalahan input, dan mendukung penyimpanan data yang terstruktur. -->

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

        {{-- EDIT MODAL --}}
        <div x-show="showEdit"
             class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
             x-transition>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-96 shadow-lg" @click.stop>

                <h2 class="text-xl font-semibold mb-4">Edit Program</h2>

                <form method="POST"
                      :action="'/admin/landing/programs/' + editData.id"
                      enctype="multipart/form-data"
                      class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-1 font-medium">Title</label>
                        <input type="text" name="title" x-model="editData.title" 
                               class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Description</label>
                        <textarea name="description" x-model="editData.description" 
                                  rows="3"
                                  class="w-full border rounded px-3 py-2"></textarea>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Tipe Konten</label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input type="radio" value="icon" x-model="editContentType" class="mr-2"> Icon
                            </label>
                            <label class="flex items-center">
                                <input type="radio" value="image" x-model="editContentType" class="mr-2"> Image
                            </label>
                        </div>
                    </div>

                    <input type="hidden" name="content_type" :value="editContentType">

                    <div x-show="editContentType === 'icon'">
                        <label class="block mb-1 font-medium">Icon HTML</label>
                        <input type="text" name="icon" x-model="editData.icon" 
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div x-show="editContentType === 'image'">
                        <input type="file" name="image" class="w-full border rounded px-3 py-2">
                        <img x-show="editData.image"
                             :src="'/storage/'+editData.image"
                             class="h-16 mt-2 rounded">
                    </div>

<!-- Pengembangan edit modal pada kode kedua menambahkan fitur Tipe Konten agar pengguna bisa memilih antara icon atau image. 
Fitur ini mencakup radio button untuk memilih tipe, input icon atau file image yang muncul sesuai pilihan, serta field hidden content_type untuk dikirim ke server. 
Dengan demikian, modal edit menjadi lebih fleksibel dan mendukung berbagai jenis konten, tanpa mengubah fungsi utama form seperti update title, description, posisi, atau status. -->

                    <div>
                        <label class="block mb-1 font-medium">Position</label>
                        <input type="number" name="position" x-model="editData.position" 
                               class="w-full border rounded px-3 py-2" 
                               min="1"
                               required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Status</label>
                        <select name="status" x-model="editData.status" 
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

    {{-- Alpine controller --}}
    <script>
        function programPage() {
            return {
                showCreate: false,
                showEdit: false,
                createContentType:'icon',
                editContentType:'icon',
                editData: {},

                openCreateModal() {
                    this.createContentType = 'icon';
                    this.showCreate = true;
                },

                // openEditModal(item) {
                //     this.editData = {
                //         id: item.id,
                //         title: item.title,
                //         description: item.description,
                //         icon: item.icon,
                //         position: item.position,
                //         status: item.status,
                //         image: item.image ?? ''
                //     };
                //     this.editData = item;
                //     this.editContentType = item.image ? 'image':'icon';
                //     this.showEdit = true;
                // }

                openEditModal(item) {
                    this.editData = item;
                    this.editContentType = item.image ? 'image' : 'icon';
                    this.showEdit = true;
                }

            };
        }
    </script>

    {{-- 
    Laporan Singkat Halaman Pengelolaan Program Studi
    
    Halaman ini menyediakan interface lengkap untuk mengelola program studi landing page dengan pendekatan modal-based 
    menggunakan Alpine.js. Sistem mendukung dua tipe konten (icon HTML atau image) yang dapat dipilih secara dinamis, 
    dengan pengelolaan posisi otomatis untuk menjaga urutan program tetap konsisten.
    
    Fitur Utama:
    - Tambah Program: Modal create dengan pilihan tipe konten (icon/image), position otomatis jika dikosongkan, 
      dan validasi lengkap untuk semua field.
    - Edit Program: Modal edit dengan preview image saat ini, dukungan perubahan tipe konten, dan pergeseran 
      posisi otomatis saat position diubah.
    - Hapus Program: Konfirmasi sebelum hapus, penghapusan file image dari storage, dan pergeseran posisi otomatis.
    - Pengelolaan File: Penghapusan otomatis file image lama saat upload file baru atau ubah ke tipe icon.
    
    Teknologi:
    - Alpine.js untuk interaktivitas modal dan conditional rendering berdasarkan tipe konten.
    - Blade template dengan validasi frontend dan backend.
    - DB transaction untuk konsistensi data posisi.
    - Storage management untuk file image dengan cleanup otomatis.
    
    Keunggulan:
    - Fleksibilitas tipe konten (icon atau image) dalam satu interface.
    - Pengelolaan posisi otomatis tanpa gap.
    - Cleanup file otomatis untuk menghemat storage.
    - User experience yang baik dengan modal interaktif dan preview image.
    --}}

</x-app-layout>
