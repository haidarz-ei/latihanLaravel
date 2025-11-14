<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 pt-6 pb-12">
        <h2 class="text-xl font-semibold mb-6 text-gray-800">Daftar eKYC Calon Mahasiswa</h2>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="border p-2">No</th>
                        <th class="border p-2">Nama</th>
                        <th class="border p-2">NIK</th>
                        <th class="border p-2">Status</th>
                        <th class="border p-2">Tanggal</th>
                        <th class="border p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($list as $i => $row)
                        <tr class="{{ $i % 2 == 0 ? 'bg-blue-50' : 'bg-white' }} hover:bg-gray-100">
                            <td class="border p-2 text-center">{{ $i + 1 }}</td>
                            <td class="border p-2">{{ $row->user->name ?? '-' }}</td>
                            <td class="border p-2">{{ $row->nik ?? '-' }}</td>
                            <td class="border p-2">
                                <span class="px-2 py-1 rounded text-black text-xs font-medium
                                    @if($row->status == 'draft') bg-gray-300
                                    @elseif($row->status == 'submitted') bg-yellow-300
                                    @elseif($row->status == 'accepted') bg-green-300
                                    @elseif($row->status == 'rejected') bg-red-300
                                    @endif">
                                    {{ ucfirst($row->status) }}
                                </span>
                            </td>
                            <td class="border p-2 text-center">
                                {{ $row->updated_at->format('d M Y H:i') }}
                            </td>
                            <td class="border p-2 text-center">
                                <a href="{{ route('admin.ekyc.show', $row->id) }}"
                                   class="text-blue-600 hover:underline text-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-blue-50">
                            <td colspan="6" class="border p-3 text-center text-gray-500">
                                Belum ada data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $list->links() }}
            </div>
        </div>
    </div>
</x-app-layout>