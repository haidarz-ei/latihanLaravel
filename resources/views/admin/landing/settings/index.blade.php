<x-app-layout>

    <div x-data="{ open:false, setting: { id: null, key:'', value:'', type:'text', status:1 } }">

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Landing Page Settings
            </h2>
        </x-slot>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- @if (session('success'))
                    <div class="mb-4 p-4 rounded bg-green-200 text-green-800">
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

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold text-lg">Landing Settings</h3>

                            {{-- **Tambah Button** --}}
                            <button 
                                @click="setting={key:'',value:'',type:'text',status:1}; open=true"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                            >
                                + Tambah Setting
                            </button>
                        </div>

                        <table class="table-auto w-full border">
                            <thead class="bg-gray-200 text-gray-700">
                                <tr>
                                    <th class="px-3 py-2">Key</th>
                                    <th class="px-3 py-2">Value</th>
                                    <th class="px-3 py-2 w-24">Type</th>
                                    <th class="px-3 py-2 w-24">Status</th>
                                    <th class="px-3 py-2 w-24 text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($settings as $setting)
                                    <tr class="border-t">
                                        <td class="px-3 py-2 font-medium">{{ $setting->key }}</td>

                                        <td class="px-3 py-2">
                                            @if($setting->type === 'image')
                                                <img src="{{ asset('storage/'.$setting->value) }}" class="h-16 rounded">
                                            @else
                                                {{ Str::limit($setting->value, 60) }}
                                            @endif
                                        </td>

                                        <td class="px-3 py-2 capitalize">{{ $setting->type }}</td>

                                        <td class="px-3 py-2">
                                            <span class="{{ $setting->status ? 'text-green-600' : 'text-gray-500' }}">
                                                {{ $setting->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>

                                        <td class="px-3 py-2 text-center">
                                            <div class="flex gap-2 justify-center">
                                                <button 
                                                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                                                    @click="open=true; setting={{ $setting->toJson() }}"
                                                >
                                                    Edit
                                                </button>
                                                <form 
                                                    method="POST" 
                                                    action="{{ route('admin.landing.settings.destroy', $setting->id) }}"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus setting ini?')"
                                                    class="inline"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button 
                                                        type="submit"
                                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"
                                                    >
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
        </div>

        {{-- MODAL --}}
        <div x-show="open" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" x-cloak>
            <div class="bg-white p-6 rounded max-w-md w-full" @click.away="open=false">

                <h2 class="text-lg font-semibold mb-4"
                    x-text="setting.id ? 'Edit Setting' : 'Tambah Setting'"></h2>

                <form method="POST"
                    :action="setting.id ? '/admin/landing/settings/' + setting.id : '{{ route('admin.landing.settings.store') }}'"
                    enctype="multipart/form-data">

                    @csrf
                    <template x-if="setting.id">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    {{-- KEY --}}
                    <div class="mb-3">
                        <label class="block mb-2 font-medium">Key</label>
                        <input type="text" name="key" x-model="setting.key"
                            :readonly="setting.id ? true : false"
                            :class="setting.id ? 'bg-gray-100' : ''"
                            class="w-full border rounded px-3 py-2"
                            placeholder="e.g. hero_title"
                            required>
                        <template x-if="setting.id">
                            <p class="text-xs text-gray-500 mt-1">Key cannot be changed</p>
                        </template>
                    </div>

                    {{-- TYPE --}}
                    <div class="mb-3">
                        <label class="block mb-2 font-medium">Type</label>
                        <select name="type" x-model="setting.type" class="w-full border rounded px-3 py-2" required>
                            <option value="text">Text</option>
                            <option value="image">Image</option>
                            <option value="json">JSON</option>
                            <option value="cta">CTA</option>
                        </select>
                    </div>

                    {{-- IMAGE --}}
                    <template x-if="setting.type === 'image'">
                        <div class="mb-3">
                            <label class="block mb-2">Upload Image</label>
                            <input type="file" name="value" accept="image/*"
                                class="w-full border rounded px-3 py-2">
                            
                            <template x-if="setting.value">
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 mb-1">Current Image:</p>
                                    <img :src="'/storage/' + setting.value" class="h-32 rounded shadow border">
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- TEXT / JSON / CTA --}}
                    <template x-if="setting.type !== 'image'">
                        <div class="mb-3">
                            <label class="block mb-2">Value</label>
                            <textarea name="value" rows="4"
                                    x-model="setting.value"
                                    class="w-full border rounded px-3 py-2"
                                    placeholder="Enter value..."></textarea>
                        </div>
                    </template>

                    {{-- STATUS --}}
                    <div class="mb-3">
                        <label class="block mb-2 font-medium">Status</label>
                        <select name="status" x-model="setting.status"
                                class="w-full border rounded px-3 py-2" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="open=false"
                                class="px-4 py-2 bg-gray-500 text-white rounded">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Save
                        </button>
                    </div>

                </form>

            </div>
        </div>
        <!-- Pengembangan modal Setting kini menggabungkan fungsi Create dan Edit dalam satu komponen. 
        Semua input (key, type, value, status) terhubung dengan Alpine.js, sehingga data otomatis muncul saat edit. 
        Tipe konten fleksibel: text, image, JSON, atau CTA, dengan preview gambar langsung dari folder storage. Modal bisa ditutup saat klik di luar, dan tombol Save menyesuaikan metode POST atau PUT berdasarkan ada tidaknya setting.id. 
         -->

    </div>

</x-app-layout>