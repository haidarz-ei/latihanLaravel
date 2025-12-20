<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingSetting;
use Illuminate\Http\Request;

class LandingSettingController extends Controller
{
    public function index()
    {
        $settings = LandingSetting::orderBy('key')->get();
        return view('admin.landing.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'key'    => 'required|string',
            'type'   => 'required|in:text,image,json,cta',
            'status' => 'required|boolean',
            'value'  => 'nullable',
        ]);

        // Validasi khusus untuk image
        if ($request->type === 'image') {
            $request->validate([
                'value' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        }

        $value = $request->value;

        if ($request->type === 'image' && $request->hasFile('value')) {
            // otomatis simpan ke storage/app/public/landing dan bikin nama unik
            $value = $request->file('value')->store('landing', 'public');
        }

        LandingSetting::create([
            'key'    => $request->key,
            'type'   => $request->type,
            'value'  => $value,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Setting berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        $setting = LandingSetting::findOrFail($id);

        $request->validate([
            'type'  => 'required|in:text,image,json,cta',
            'value' => 'nullable',
        ]);

        $value = $request->value;

        // Handle image upload
        if ($request->type === 'image') {
            if ($request->hasFile('value')) {
                // Hapus file lama jika ada
                if ($setting->value && \Storage::disk('public')->exists($setting->value)) {
                    \Storage::disk('public')->delete($setting->value);
                }
                // Simpan file baru
                $value = $request->file('value')->store('landing', 'public');
            } else {
                // Jika tidak ada file baru, tetap gunakan value lama
                $value = $setting->value;
            }
        }

        $setting->value = $value;
        $setting->type  = $request->type;
        $setting->status = $request->status ?? 1;
        $setting->save();

        return redirect()->back()->with('success', 'Setting berhasil diupdate');
    }

    public function destroy($id)
    {
        $setting = LandingSetting::findOrFail($id);

        // Hapus file image jika ada
        if ($setting->type === 'image' && $setting->value) {
            if (\Storage::disk('public')->exists($setting->value)) {
                \Storage::disk('public')->delete($setting->value);
            }
        }

        $setting->delete();

        return redirect()->back()->with('success', 'Setting berhasil dihapus');
    }
}




/*
Deskripsi Singkat Kode Pertama
Kode pertama menerapkan konsep manajemen setting terpusat. Seluruh proses tambah dan ubah data dilakukan dari satu halaman utama tanpa halaman edit terpisah. Admin dapat mengubah type, value, dan status dalam satu alur yang sama.

Ciri utama kode pertama:
- Tidak memiliki method edit().
- Update dilakukan langsung dari halaman index melalui modal.
- Mendukung perubahan type saat update.
- Mendukung tipe data text, image, json, dan cta.
- Status setting dapat diaktifkan atau dinonaktifkan baik saat create maupun update.
- Validasi image yang ketat dengan pembatasan tipe file (jpeg, png, jpg, gif, svg) dan ukuran maksimum 2MB.
- Saat update image, jika tidak ada file baru yang diupload, image lama tetap digunakan.
- Saat update image dengan file baru, file lama otomatis dihapus untuk menghemat storage.
- Memiliki fitur hapus (destroy) dengan konfirmasi di frontend.
- Saat menghapus setting dengan tipe image, file image juga otomatis dihapus dari storage.

Deskripsi Singkat Kode Kedua
Kode kedua menerapkan konsep CRUD yang lebih terstruktur. Proses update dilakukan melalui halaman edit khusus, dan perubahan data dibatasi sesuai tipe setting yang sudah ada.

Ciri utama kode kedua:
- Memiliki method edit() dan view terpisah.
- type setting tidak dapat diubah saat update.
- Validasi disesuaikan dengan tipe data lama.
- Tidak mengelola perubahan status saat update.

Perbandingan Alur Edit Data
Pada kode pertama, admin dapat langsung mengubah data setting dari halaman daftar tanpa masuk ke halaman edit. Hal ini membuat proses lebih cepat, namun berpotensi menimbulkan kesalahan karena perubahan type diperbolehkan.
Pada kode kedua, admin harus masuk ke halaman edit terlebih dahulu. Pendekatan ini lebih aman karena admin hanya mengubah nilai (value) tanpa mengubah struktur data (type).

Perbandingan Validasi Data
Kode pertama menggunakan validasi yang fleksibel namun tetap aman. Untuk tipe text, json, dan cta, validasi relatif longgar. Namun untuk tipe image, validasi sudah sangat ketat dengan pembatasan tipe file (mimes:jpeg,png,jpg,gif,svg) dan ukuran maksimum 2MB (max:2048). Hal ini meningkatkan keamanan dan konsistensi data.
Kode kedua menggunakan validasi yang lebih ketat, khususnya untuk image, dengan pembatasan tipe file dan ukuran maksimum. Hal ini meningkatkan keamanan dan konsistensi data.

Perbandingan Pengelolaan Tipe Data
Kode pertama mendukung tipe json dan cta secara eksplisit dan memungkinkan admin mengubah tipe data saat update. Pendekatan ini cocok untuk sistem setting dinamis.
Kode kedua tidak memberikan perlakuan khusus terhadap json dan mengunci tipe data sejak awal pembuatan setting. Pendekatan ini cocok untuk sistem setting statis.

Perbandingan Pengelolaan Status
Pada kode pertama, status setting (aktif/nonaktif) dikelola baik saat create maupun update. Admin dapat mengubah status kapan saja melalui form yang sama.
Pada kode kedua, status hanya dikelola saat create dan tidak dapat diubah saat update.

Perbandingan Pengelolaan File Image
Pada kode pertama, saat update image:
- Jika ada file baru yang diupload, file lama otomatis dihapus dan file baru disimpan.
- Jika tidak ada file baru yang diupload, file lama tetap digunakan (tidak dihapus).
- Hal ini mencegah kehilangan data image secara tidak sengaja.
Pada kode kedua, pengelolaan file image mungkin berbeda tergantung implementasinya.

Perbandingan Fitur Hapus
Pada kode pertama, fitur hapus (destroy) tersedia dengan:
- Konfirmasi di frontend sebelum menghapus untuk mencegah penghapusan tidak sengaja.
- Penghapusan otomatis file image dari storage jika setting yang dihapus bertipe image.
- Hal ini mencegah penumpukan file yang tidak terpakai di storage.
Pada kode kedua, fitur hapus mungkin tidak tersedia atau memiliki implementasi yang berbeda.
*/
