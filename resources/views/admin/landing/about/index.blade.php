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

            {{-- EDIT ABOUT SECTION --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Edit About</h3>

                    @if($about)
                        <form method="POST" action="{{ route('admin.landing.about.update', $about->id) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block mb-1 font-medium">Judul</label>
                                <input type="text" name="title" value="{{ old('title', $about->title) }}" class="border-gray-300 rounded-md w-full">
                            </div>

                            <div>
                                <label class="block mb-1 font-medium">Paragraf 1</label>
                                <textarea name="paragraph_1" rows="3" class="border-gray-300 rounded-md w-full">{{ old('paragraph_1', $about->paragraph_1) }}</textarea>
                            </div>

                            <div>
                                <label class="block mb-1 font-medium">Paragraf 2 <span class="text-gray-500 text-sm">(Opsional)</span></label>
                                <textarea name="paragraph_2" rows="3" class="border-gray-300 rounded-md w-full">{{ old('paragraph_2', $about->paragraph_2) }}</textarea>
                            </div>

                            <div>
                                <label class="block mb-1 font-medium">Gambar <span class="text-gray-500 text-sm">(Opsional)</span></label>
                                <input type="file" name="image" class="border-gray-300 rounded-md w-full">
                                
                                @if($about->image)
                                    <img src="{{ asset('storage/' . $about->image) }}" class="w-32 h-32 object-cover rounded mt-2">
                                @endif
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update About</button>
                            </div>
                        </form>
                    @else
                        <p class="text-gray-500">Data about belum ada. Silakan jalankan seeder terlebih dahulu.</p>
                    @endif
                </div>
            </div>

            {{-- DESKRIPSI TENTANG SECTION --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Deskripsi Tentang</h3>
                        <button @click="openCreateDescriptionModal()"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            + Tambah Deskripsi
                        </button>
                    </div>

                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">Deskripsi</th>
                                <th class="px-4 py-2 border">Posisi</th>
                                <th class="px-4 py-2 border text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($descriptions as $desc)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="border px-4 py-2">{{ $desc->description }}</td>
                                    <td class="border px-4 py-2">{{ $desc->position }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        <button @click='openEditDescriptionModal(@json($desc))'
                                                class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-1">
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.landing.about.description.destroy', $desc->id) }}"
                                            method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Hapus deskripsi ini?')"
                                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="border px-4 py-2 text-center text-gray-500">
                                        Belum ada deskripsi tentang
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL CREATE DESKRIPSI -->
        <div x-show="showCreateDescription" x-transition x-cloak
             class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Tambah Deskripsi Tentang</h2>

                <form method="POST" action="{{ route('admin.landing.about.description.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block mb-1 font-medium">Deskripsi</label>
                        <textarea name="description" rows="4" class="border-gray-300 rounded-md w-full" required></textarea>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Posisi <span class="text-gray-500 text-sm">(Opsional)</span></label>
                        <input type="number" name="position" class="border-gray-300 rounded-md w-full" placeholder="Auto">
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showCreateDescription = false"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT DESKRIPSI -->
        <div x-show="showEditDescription" x-transition x-cloak
             class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Edit Deskripsi Tentang</h2>

                <form method="POST" :action="'/admin/landing/about/description/' + editDescriptionData.id" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-1 font-medium">Deskripsi</label>
                        <textarea name="description" x-model="editDescriptionData.description" rows="4" class="border-gray-300 rounded-md w-full" required></textarea>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Posisi</label>
                        <input type="number" name="position" x-model="editDescriptionData.position" class="border-gray-300 rounded-md w-full">
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showEditDescription = false" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Update</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function aboutPage() {
            return {
                showCreateDescription: false,
                showEditDescription: false,
                editDescriptionData: {},

                openCreateDescriptionModal() {
                    this.showCreateDescription = true;
                },
                openEditDescriptionModal(item) {
                    this.editDescriptionData = {
                        id: item.id,
                        description: item.description,
                        position: item.position
                    };
                    this.showEditDescription = true;
                }
            }
        }
    </script>

</x-app-layout>

