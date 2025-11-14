{{-- buat file baru step4.blade.php --}}
<x-app-layout>
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow mt-8">
        <h2 class="text-xl font-semibold mb-4 text-center">
            Step 4 - Alamat Domisili & Referensi Pendaftaran
        </h2>


        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('ekyc.step4.store') }}">
            @csrf

            {{-- Alamat Domisili Lengkap --}}
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Alamat Domisili Lengkap</label>
                <textarea name="alamat_domisili" class="w-full border-gray-300 rounded-md">{{ old('alamat_domisili', $data->alamat_domisili) }}</textarea>
            </div>

            {{-- Provinsi --}}
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Provinsi</label>
                <select name="provinsi" id="provinsi" class="w-full border-gray-300 rounded-md">
                    <option value="">-- Pilih Provinsi --</option>
                    @foreach ($provinsiList as $prov)
                        <option value="{{ $prov }}" {{ old('provinsi', $data->provinsi) == $prov ? 'selected' : '' }}>
                            {{ $prov }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Kota --}}
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Kota</label>
                <select name="kota_kab" id="kota" class="w-full border-gray-300 rounded-md">
                    <option value="">-- Pilih Kota --</option>
                    @foreach ($kotaList as $kota)
                        <option value="{{ $kota }}" {{ old('kota_kab', $data->kota_kab) == $kota ? 'selected' : '' }}>
                            {{ $kota }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Kecamatan --}}
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Kecamatan</label>
                <select name="kecamatan" id="kecamatan" class="w-full border-gray-300 rounded-md">
                    <option value="">-- Pilih Kecamatan --</option>
                    @foreach ($kecamatanList as $kec)
                        <option value="{{ $kec }}" {{ old('kecamatan', $data->kecamatan) == $kec ? 'selected' : '' }}>
                            {{ $kec }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Kode Pos --}}
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Kode Pos</label>
                <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos', $data->kode_pos) }}" readonly>   
            </div>

            {{-- Nama Ibu Kandung --}}
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Nama Ibu Kandung</label>
                <input type="text" name="nama_ibu" id="nama_ibu" value="{{ old('nama_ibu', $data->nama_ibu) }}">
            </div>

            {{-- Referensi Informasi --}} 
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Sumber Informasi Pendaftaran</label>
                <select name="sumber_informasi" id="sumber_informasi">
                    <option value="">-- Pilih Sumber Informasi --</option>
                    <option value="Teman" {{ old('sumber_informasi', $data->sumber_informasi) == 'Teman' ? 'selected' : '' }}>Teman</option>
                    <option value="Sosial Media" {{ old('sumber_informasi', $data->sumber_informasi) == 'Sosial Media' ? 'selected' : '' }}>Sosial Media</option>
                    <option value="Langsung dari Kampus" {{ old('sumber_informasi', $data->sumber_informasi) == 'Langsung dari Kampus' ? 'selected' : '' }}>Langsung dari Kampus</option>
                </select>
            </div>

            {{-- Tombol Navigasi --}}
            <div class="flex justify-between items-center mt-4">
                <a href="{{ route('ekyc.step3') }}" class="mr-4 inline-block bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 transition duration-200">
                    Back
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Simpan & Lanjut Step 5
                </button>
            </div>
        </form>
    </div>

{{-- Script Dinamis (Provinsi → Kota → Kecamatan → Kode Pos) --}}
<script>
    const alamatData = @json($alamatList);

    document.getElementById('provinsi').addEventListener('change', function () {
        const prov = this.value;
        const kotaSelect = document.getElementById('kota');
        kotaSelect.innerHTML = '<option value="">-- Pilih Kota --</option>';

        const filteredKota = alamatData.filter(item => item.provinsi === prov);
        filteredKota.forEach(item => {
            kotaSelect.innerHTML += `<option value="${item.kota}">${item.kota}</option>`;
        });
    });

    document.getElementById('kota').addEventListener('change', function () {
        const kota = this.value;
        const kecSelect = document.getElementById('kecamatan');
        kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

        const filteredKec = alamatData.filter(item => item.kota === kota);
        filteredKec.forEach(item => {
            kecSelect.innerHTML += `<option value="${item.kecamatan}">${item.kecamatan}</option>`;
        });
    });

    document.getElementById('kecamatan').addEventListener('change', function () {
        const kec = this.value;
        const kodeInput = document.getElementById('kode_pos');
        const selected = alamatData.find(item => item.kecamatan === kec);
        kodeInput.value = selected ? selected.kode_pos : '';
    });
</script>
</x-app-layout>
