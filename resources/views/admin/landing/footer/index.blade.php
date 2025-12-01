<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Footer Links') }}
        </h2>
    </x-slot>

    <div x-data="footerPage()" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6">
                <button @click="openCreateModal()"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Tambah Link
                </button>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Daftar Footer Links</h3>

                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">Pos</th>
                                <th class="px-4 py-2 border">Label</th>
                                <th class="px-4 py-2 border">URL</th>
                                <th class="px-4 py-2 border text-center">Status</th>
                                <th class="px-4 py-2 border text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($links as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="border px-4 py-2 text-center">{{ $item->position }}</td>
                                    <td class="border px-4 py-2">{{ $item->label }}</td>
                                    <td class="border px-4 py-2">{{ $item->url }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        <span class="px-2 py-1 rounded text-white {{ $item->status ? 'bg-green-600' : 'bg-red-600' }}">
                                            {{ $item->status ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="border px-4 py-2 text-center">

                                        {{-- EDIT --}}
                                        <button @click="openEditModal({{ $item }})"
                                            class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-1">
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.landing.footer.destroy', $item->id) }}"
                                              method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Hapus link ini?')"
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
                <h2 class="text-xl font-semibold mb-4">Tambah Footer Link</h2>

                <form method="POST" action="{{ route('admin.landing.footer.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block mb-1 font-medium">Label</label>
                        <input type="text" name="label" class="border-gray-300 rounded-md w-full" required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">URL</label>
                        <input type="text" name="url" class="border-gray-300 rounded-md w-full">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Status</label>
                        <select name="status" class="border-gray-300 rounded-md w-full">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showCreate = false"
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

        <!-- MODAL EDIT -->
        <div x-show="showEdit" x-transition
             class="fixed inset-0 bg-black/50 flex items-center justify-center p-4">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg shadow-lg">
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

                <form method="POST" :action="'/admin/landing/footer/' + editData.id" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-1 font-medium">Label</label>
                        <input type="text" name="label" x-model="editData.label"
                               class="border-gray-300 rounded-md w-full" required>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">URL</label>
                        <input type="text" name="url" x-model="editData.url"
                               class="border-gray-300 rounded-md w-full">
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Position</label>
                        <input type="number" name="position" x-model="editData.position"
                            class="border-gray-300 rounded-md w-full" required>
                    </div>


                    <div>
                        <label class="block mb-1 font-medium">Status</label>
                        <select name="status" x-model="editData.status"
                                class="border-gray-300 rounded-md w-full">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showEdit = false"
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
                    this.editData = {
                        id: item.id,
                        label: item.label,
                        url: item.url,
                        status: item.status,
                        position: item.position,
                    };
                    this.showEdit = true;
                }
            }
        }
    </script>
</x-app-layout>