<x-app-layout>
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow mt-8">
        <h2 class="mt-8 border-t pt-6 px-4 text-2xl font-semibold mb-6">
            Detail eKYC - {{ $data->user->name ?? '-' }}
        </h2>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 mb-6 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Data Pribadi -->
        <section class="mt-8 border-t pt-6 px-4">
            <h3 class="text-lg font-semibold mb-4">Data Pribadi</h3>
            <div class="space-y-3">
                @foreach ([
                    'Nama Lengkap' => $data->nama,
                    'NIK' => $data->nik,
                    'Tanggal Lahir' => $data->tanggal_lahir,
                    'Alamat' => $data->alamat,
                ] as $label => $value)
                    <div class="flex items-start">
                        <label class="w-48 text-sm font-medium text-gray-700 mt-1">{{ $label }}:</label>
                        @if(str_contains(strtolower($label), 'alamat'))
                            <textarea rows="2" readonly class="flex-1 border border-gray-300 rounded-md p-2 text-sm bg-gray-50">{{ $value }}</textarea>
                        @else
                            <input type="text" readonly value="{{ $value }}" class="flex-1 border border-gray-300 rounded-md p-2 text-sm bg-gray-50">
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Data Pendidikan -->
        <section class="mt-8 border-t pt-6 px-4">
            <h3 class="text-lg font-semibold mb-4">Data Pendidikan</h3>
            <div class="space-y-3">
                @foreach ([
                    'Asal SD' => $data->asal_sd,
                    'Asal SMP' => $data->asal_smp,
                    'Asal SMA' => $data->asal_sma,
                ] as $label => $value)
                    <div class="flex items-start">
                        <label class="w-48 text-sm font-medium text-gray-700 mt-1">{{ $label }}:</label>
                        <input type="text" readonly value="{{ $value }}" class="flex-1 border border-gray-300 rounded-md p-2 text-sm bg-gray-50">
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Dokumen eKYC -->
        <section class="mt-8 border-t pt-6 px-4">
            <h3 class="text-lg font-semibold mb-4">Dokumen eKYC</h3>
            <div class="flex flex-wrap gap-6">
                @foreach ([
                    'File_ktp' => 'file_ktp',
                    'File_selfie' => 'file_selfie',
                    'File_kk' => 'file_kk',
                    'File_ijazah' => 'file_ijazah',
                ] as $label => $field)
                    @if ($data->$field)
                        <div class="flex flex-col items-center w-[110px]">
                            <p class="text-xs font-medium mb-1">{{ $label }}</p>
                            <a href="{{ asset('storage/' . $data->$field) }}" target="_blank" class="border rounded-md overflow-hidden shadow-sm hover:shadow-lg">
                                <img src="{{ asset('storage/' . $data->$field) }}" class="w-28 h-36 object-contain bg-gray-100">
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>

        <!-- Alamat Domisili -->
        <section class="mt-8 border-t pt-6 px-4">
            <h3 class="text-lg font-semibold mb-4">Alamat Domisili</h3>
            <div class="space-y-3">
                @foreach ([
                    'Alamat Domisili' => $data->alamat_domisili,
                    'Provinsi' => $data->provinsi,
                    'Kota' => $data->kota_kab,
                    'Kecamatan' => $data->kecamatan,
                    'Kode Pos' => $data->kode_pos,
                    'Nama Ibu Kandung' => $data->nama_ibu,
                    'Referensi Sumber' => $data->sumber_informasi,
                ] as $label => $value)
                    <div class="flex items-start">
                        <label class="w-48 text-sm font-medium text-gray-700 mt-1">{{ $label }}:</label>
                         @if(str_contains(strtolower($label), 'alamat'))
                            <textarea rows="2" readonly class="flex-1 border border-gray-300 rounded-md p-2 text-sm bg-gray-50">{{ $value }}</textarea>
                        @else
                            <input type="text" readonly value="{{ $value }}" class="flex-1 border border-gray-300 rounded-md p-2 text-sm bg-gray-50">
                        @endif
                    </div>
                @endforeach
            </div>
        </section>


        <!-- Status Verifikasi -->
        
        <section class="mt-12 border-t pt-6 px-4 pb-8">
            <h3 class="text-lg font-semibold mb-4">Status Verifikasi</h3>
            <form action="{{ route('admin.ekyc.verify', $data->id) }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <div class="flex items-center gap-4">
                    <label class="w-48 text-sm font-medium text-gray-700">Ubah Status</label>
                    <select name="status" class="flex-1 border border-gray-300 rounded-md p-2 text-sm">
                        <option value="Accepted" {{ $data->status == 'accepted' ? 'selected' : '' }}>Diterima</option>
                        <option value="Submitted" {{ $data->status == 'submitted' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="Draft" {{ $data->status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Rejected" {{ $data->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="flex items-center gap-4 mt-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                        Simpan
                    </button>
                    <a href="{{ route('admin.ekyc.index') }}" class="text-sm text-gray-600 hover:underline ml-auto">
                        Kembali
                    </a>
                </div>
            </form>
        </section>

    </div>
</x-app-layout>