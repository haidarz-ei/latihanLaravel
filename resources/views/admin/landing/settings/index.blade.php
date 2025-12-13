<x-app-layout>

    <div x-data="{ open:false, setting:null }">

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Landing Settings
            </h2>
        </x-slot>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

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

                <div class="mb-6">
                    <button @click="setting={key:'', value:'', type:'text', status:1}; open=true" class="px-4 py-2 bg-blue-600 text-white rounded">
                        + Tambah Setting
                    </button>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                    <div class="p-6">

                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Landing Settings</h3>
                        </div>

                        <table class="table-auto w-full border">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="px-3 py-2">Key</th>
                                    <th class="px-3 py-2">Value</th>
                                    <th class="px-3 py-2">Type</th>
                                    <th class="px-3 py-2">Status</th>
                                    <th class="px-3 py-2">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($settings as $setting)
                                    <tr class="border-t">
                                        <td class="px-3 py-2">{{ $setting->key }}</td>

                                        <td class="px-3 py-2">
                                            @if($setting->type === 'image')
                                                <img src="{{ asset('storage/'.$setting->value) }}" class="h-16 rounded">
                                            @else
                                                {{ Str::limit($setting->value, 60) }}
                                            @endif
                                        </td>

                                        <td class="px-3 py-2">{{ $setting->type }}</td>

                                        <td class="px-3 py-2">
                                            {{ $setting->status ? 'Active' : 'Inactive' }}
                                        </td>

                                        <td class="px-3 py-2">
                                            <button
                                                @click="open=true; setting={{ $setting->toJson() }}"
                                                class="px-3 py-1 bg-blue-600 text-white rounded text-xs">
                                                Edit
                                            </button>
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
        <div x-show="open" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
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

                    <input type="hidden" name="type" x-model="setting.type">

                    <div class="mb-3">
                        <label>Key</label>
                        <input type="text" name="key" x-model="setting.key"
                               class="w-full border rounded px-3 py-2">
                    </div>

                    {{-- IMAGE --}}
                    <template x-if="setting.type === 'image'">
                        <div class="mb-3">
                            <label>Upload Image</label>
                            <input type="file" name="value" accept="image/*">

                            <template x-if="setting.value">
                                <img :src="'/storage/' + setting.value"
                                     class="h-20 mt-2 rounded">
                            </template>
                        </div>
                    </template>

                    {{-- TEXT / JSON --}}
                    <template x-if="setting.type !== 'image'">
                        <div class="mb-3">
                            <label>Value</label>
                            <textarea name="value" rows="4"
                                      x-model="setting.value"
                                      class="w-full border rounded px-3 py-2"></textarea>
                        </div>
                    </template>

                    <div class="mb-3">
                        <label>Type</label>
                        <select x-model="setting.type" class="w-full border rounded px-3 py-2">
                            <option value="text">Text</option>
                            <option value="image">Image</option>
                            <option value="json">JSON</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label>Status</label>
                        <select name="status" x-model="setting.status"
                                class="w-full border rounded px-3 py-2">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="open=false"
                                class="px-4 py-2 bg-gray-500 text-white rounded">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded">
                            Save
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

</x-app-layout>
