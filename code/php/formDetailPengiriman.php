<?php
session_start();
include 'koneksi.php';
require_once 'config.php';
require_once 'getShippingRates.php';

$kode = $_GET['kode'] ?? '';
$data = [];
if ($kode && preg_match('/^PKT-\d{4}$/', $kode)) {
    $stmt = $conn->prepare("SELECT p.*, peng.nama_pengirim, peng.no_hp_pengirim, peng.detail_alamat_pengirim, peng.postal_pengirim, peng.pin_point_pengirim, pen.nama_penerima, pen.no_hp_penerima, pen.kecamatan, pen.kota_kabupaten, pen.provinsi, pen.detail_alamat_penerima, pen.postal_penerima, pen.pin_point_penerima, b.jenis_barang, b.jumlah_barang, b.berat, b.panjang, b.lebar, b.tinggi, b.layanan_pengiriman, b.kurir FROM paket p JOIN pengirim peng ON p.id_pengirim = peng.id JOIN penerima pen ON p.id_penerima = pen.id JOIN barang b ON p.id_barang = b.id WHERE p.kodePaket = ?");
    $stmt->bind_param("s", $kode);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc() ?: [];
    $data['cod'] = $data['cod'] ?? 0;
    $data['packing_kayu'] = $data['packing_kayu'] ?? 0;
    $data['asuransi'] = $data['asuransi'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Detail Pengiriman</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
  <style>
        body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f5f7fb;
      color: #333;
    }

    .container {
      max-width: 800px;
      margin: auto;
      padding: 20px;
    }

    .card {
      background: white;
      border: 1px solid #e2e2e2;
      border-radius: 10px;
      padding: 24px;
      margin-bottom: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .card h2 {
      margin-top: 0;
      font-size: 18px;
      display: flex;
      align-items: center;
    }

    .card h2::before {
      content: '✔️';
      margin-right: 12px;
    }

    .header {
      display: flex;
      align-items: center;
      justify-content: flex-start;
      font-size: 20px;
      font-weight: bold;
      padding: 12px 0;
    }

    .header i {
      font-size: 24px;
      cursor: pointer;
      margin-right: 25px;
    }

    .header .tambah-paket {
      font-size: 30px;
      font-weight: bold;
    }

    h2 {
      font-size: 18px;
      margin-top: 30px;
      margin-bottom: 10px;
    }

    label {
      display: block;
      margin-bottom: 4px;
      font-size: 14px;
      font-weight: 500;
    }

    input[type="text"],
    input[type="number"],
    select,
    textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 16px;
      border: 1.5px solid #333;
      border-radius: 6px;
      font-size: 14px;
      box-sizing: border-box;
    }

    textarea {
      resize: vertical;
    }

    .row {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }

    .row > div {
      flex: 1;
      min-width: 0;
    }

    .select-3col > div {
      flex: 1;
      min-width: calc(33.33% - 8px);
    }

    .select-3col > div input {
      min-width: 100%;
    }

    .kode-paket {
      background-color: #3f70ff;
      color: white;
      font-weight: bold;
      padding: 8px 14px;
      border-radius: 12px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      float: right;
      margin-top: -20px;
    }

    .checkbox-group {
      margin-bottom: 16px;
    }

    .checkbox-group label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      margin-bottom: 8px;
      cursor: pointer;
    }

    .checkbox-group input[type="checkbox"] {
      transform: scale(1.2);
      margin: 0;
    }

    .checkbox-group small {
      display: block;
      margin: 6px 0 12px 28px;
      color: #666;
      font-size: 12px;
    }

    .footer-total {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 24px;
      padding: 12px 0;
      border-top: 1px solid #e2e2e2;
    }

    .footer-total .left {
      font-size: 18px;
      font-weight: bold;
    }

    .footer-total .right {
      text-align: right;
    }

    .footer-total .price-link {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .footer-total .price {
      font-size: 18px;
      font-weight: bold;
      color: #3f70ff;
    }

    .footer-total a {
      font-size: 12px;
      color: #3f70ff;
      text-decoration: none;
    }

    .footer-total .btn-lanjut {
      background-color: #3f70ff;
      color: white;
      padding: 12px 24px;
      border-radius: 6px;
      font-weight: bold;
      font-size: 16px;
      border: none;
      cursor: pointer;
      margin-top: 8px;
      width: 100%;
    }

    .footer-total .btn-lanjut:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }
    .footer-total .btn-lanjut:hover:not(:disabled) {
      background-color: #2c54d4; /* Darker blue on hover */
    }

    .status-box {
      background-color: #f9f9f9;
      padding: 12px;
      border-radius: 6px;
      margin-bottom: 12px;
      font-size: 14px;
      color: #555;
      display: none;
    }

    .status-box.active {
      display: block;
    }

    .status-box a {
      color: #3f70ff;
      text-decoration: none;
    }

    .button-group {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .button-group button {
      flex: 1;
      background-color: #f1f1f1;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 10px;
      font-size: 14px;
      cursor: pointer;
      color: #333;
      white-space: nowrap;
    }

    .button-group button:hover {
      background-color: #e0e0e0;
    }

    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background: white;
      padding: 24px;
      border-radius: 10px;
      max-width: 400px;
      width: 90%;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      position: relative;
    }

    .modal-buttons {
      display: flex;
      gap: 12px;
      margin-top: 12px;
    }

    .btn-cancel {
      flex: 1;
      background-color: #f1f1f1;
      color: #333;
      padding: 10px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .btn-save {
      flex: 1;
      background-color: #3f70ff;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .popup {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }

    .popup-content {
      background: white;
      padding: 24px;
      border-radius: 10px;
      max-width: 600px;
      width: 90%;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      position: relative;
    }

    .close {
      position: absolute;
      top: 12px;
      right: 12px;
      font-size: 24px;
      cursor: pointer;
      color: #aaa;
    }

    .close:hover {
      color: #333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 12px;
    }

    table th, table td {
      padding: 8px;
      border: 1px solid #ddd;
      text-align: left;
      font-size: 14px;
    }

    table th {
      background-color: #f1f1f1;
      font-weight: bold;
    }

    .btn-select {
      background-color: #3f70ff;
      color: white;
      padding: 6px 12px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .btn-select:hover {
      background-color: #2962ff;
    }

    .select-4col > div {
      flex: 1;
      min-width: calc(25% - 9px);
    }

    .select-4col > div input {
      min-width: 100%;
    }

    .status-box {
      background-color: #f9f9f9;
      padding: 12px;
      border-radius: 6px;
      margin-bottom: 12px;
      font-size: 14px;
      color: #555;
      display: none;
    }

    .status-box.active {
      display: block;
    }

    .status-box a {
      color: #3f70ff;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <i class="fas fa-angle-left" onclick="window.history.back()"></i>
      <span class="tambah-paket">Detail Pengiriman</span>
    </div>
    <div class="kode-paket">
      <i class="fas fa-box"></i>
      Kode Paket : <span id="kodePaket"><?= htmlspecialchars($kode) ?></span>
    </div>

    <form id="formPengiriman" action="simpanDetailPaket.php" method="POST" onsubmit="return handleSubmit(event)">
      <!-- Hidden inputs for pengirim and penerima data -->
      <input type="hidden" name="nama_pengirim" value="<?= htmlspecialchars($data['nama_pengirim'] ?? '') ?>">
      <input type="hidden" name="no_hp_pengirim" value="<?= htmlspecialchars($data['no_hp_pengirim'] ?? '') ?>">
      <input type="hidden" name="detail_alamat_pengirim" value="<?= htmlspecialchars($data['detail_alamat_pengirim'] ?? '') ?>">
      <input type="hidden" name="postal_pengirim" value="<?= htmlspecialchars($data['postal_pengirim'] ?? '') ?>">
      <input type="hidden" name="pin_point_pengirim" value="<?= htmlspecialchars($data['pin_point_pengirim'] ?? '') ?>">

      <input type="hidden" name="nama_penerima" value="<?= htmlspecialchars($data['nama_penerima'] ?? '') ?>">
      <input type="hidden" name="no_hp_penerima" value="<?= htmlspecialchars($data['no_hp_penerima'] ?? '') ?>">
      <input type="hidden" name="kecamatan" value="<?= htmlspecialchars($data['kecamatan'] ?? '') ?>">
      <input type="hidden" name="kota_kabupaten" value="<?= htmlspecialchars($data['kota_kabupaten'] ?? '') ?>">
      <input type="hidden" name="provinsi" value="<?= htmlspecialchars($data['provinsi'] ?? '') ?>">
      <input type="hidden" name="detail_alamat_penerima" value="<?= htmlspecialchars($data['detail_alamat_penerima'] ?? '') ?>">
      <input type="hidden" name="postal_penerima" value="<?= htmlspecialchars($data['postal_penerima'] ?? '') ?>">
      <input type="hidden" name="pin_point_penerima" value="<?= htmlspecialchars($data['pin_point_penerima'] ?? '') ?>">

      <!-- Informasi Pengirim -->
      <div class="card">
        <h2>Alamat Pengirim</h2>
        <div id="pengirimStatus" class="status-box">
          <?= htmlspecialchars($data['nama_pengirim'] ?? '') ?><br>
          <?= htmlspecialchars($data['no_hp_pengirim'] ?? '') ?><br>
          <?= htmlspecialchars($data['detail_alamat_pengirim'] ?? '') ?><br>
          Kode Pos: <?= htmlspecialchars($data['postal_pengirim'] ?? '') ?><br>
          Pin Point: <a href="<?= htmlspecialchars($data['pin_point_pengirim'] ?? '#') ?>" target="_blank">Lihat Peta</a>
        </div>
        <div class="button-group">
          <button type="button" onclick="bukaAddressModal('pengirim')">Gunakan Informasi yang Sudah Ada</button>
          <button type="button" onclick="bukaForm('pengirim', 'baru')">Tambah Informasi Baru</button>
        </div>
      </div>

      <!-- Informasi Penerima -->
      <div class="card">
        <h2>Data Penerima</h2>
        <div id="penerimaStatus" class="status-box">
          <?= htmlspecialchars($data['nama_penerima'] ?? '') ?><br>
          <?= htmlspecialchars($data['no_hp_penerima'] ?? '') ?><br>
          <?= htmlspecialchars($data['kecamatan'] ?? '') ?>, <?= htmlspecialchars($data['kota_kabupaten'] ?? '') ?>, <?= htmlspecialchars($data['provinsi'] ?? '') ?><br>
          <?= htmlspecialchars($data['detail_alamat_penerima'] ?? '') ?><br>
          Kode Pos: <?= htmlspecialchars($data['postal_penerima'] ?? '') ?><br>
          Pin Point: <a href="<?= htmlspecialchars($data['pin_point_penerima'] ?? '#') ?>" target="_blank">Lihat Peta</a>
        </div>
        <div class="button-group">
          <button type="button" onclick="bukaAddressModal('penerima')">Gunakan Informasi yang Sudah Ada</button>
          <button type="button" onclick="bukaForm('penerima', 'baru')">Tambah Informasi Baru</button>
        </div>
      </div>

      <!-- Modal untuk Pengirim/Penerima -->
      <div class="modal" id="formModal">
        <div class="modal-content">
          <h2 id="modalTitle">Form Informasi</h2>
          <form id="formAlamat">
            <input type="hidden" id="formType" name="formType">
            <label>Nama</label>
              <input type="text" id="nama_pengirim" name="nama_pengirim" required placeholder="Nama lengkap"/>
            <label>Nomor HP</label>
              <input type="text" id="no_hp_pengirim" name="no_hp_pengirim" required placeholder="Contoh: 8123456789"/>
            <div class="row select-3col" id="penerimaFields" style="display: none;">
              <div>
                <label>Provinsi</label>
                  <input type="text" id="provinsi" name="provinsi" placeholder="Contoh: Jawa Barat" list="provinsi-list"/>
                <datalist id="provinsi-list"></datalist>
              </div>
              <div>
                <label>Kota/Kabupaten</label>
                  <input type="text" id="kota_kabupaten" name="kota_kabupaten" placeholder="Contoh: Bandung" list="kota-list"/>
                <datalist id="kota-list"></datalist>
              </div>
              <div>
                <label>Kecamatan</label>
                <input type="text" id="kecamatan" name="kecamatan" placeholder="Contoh: Cihampelas" list="kecamatan-list"/>
                <datalist id="kecamatan-list"></datalist>
              </div>
            </div>
            <label>Alamat</label>
            <textarea id="detail_alamat_pengirim" name="detail_alamat_pengirim" required placeholder="Masukkan alamat lengkap"></textarea>
            <label>Postal Code</label>
            <input type="text" id="postal_pengirim" name="postal_pengirim" required placeholder="Contoh: 15157"/>
            <label>Pin Point Alamat (URL Google Maps)</label>
            <input type="text" id="pin_point_pengirim" name="pin_point_pengirim" placeholder="Contoh: https://maps.google.com/?q=-6.200000,106.800000"/>
            <div class="modal-buttons">
              <button type="button" class="btn-cancel" onclick="tutupForm()">Batal</button>
              <button type="button" class="btn-save" onclick="saveModalData()">Simpan</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Modal untuk Pilih Alamat Tersimpan -->
      <div class="modal" id="addressModal">
        <div class="modal-content">
          <h2 id="addressModalTitle">Pilih Alamat Tersimpan</h2>
          <div id="addressList" style="max-height: 400px; overflow-y: auto;">
            <!-- Addresses will be loaded here -->
          </div>
          <div class="modal-buttons">
            <button type="button" class="btn-cancel" onclick="tutupAddressModal()">Batal</button>
          </div>
        </div>
      </div>

      <!-- Detail Barang -->
      <h2>Detail Barang</h2>
      <div class="row">
        <div>
          <label>Jenis Barang</label>
          <input name="jenis_barang" type="text" value="<?= htmlspecialchars($data['jenis_barang'] ?? '') ?>" required placeholder="Contoh: Pakaian"/>
        </div>
        <div>
          <label>Jumlah Barang</label>
          <input name="jumlah_barang" type="number" min="1" value="<?= htmlspecialchars($data['jumlah_barang'] ?? '1') ?>" required placeholder="Contoh: 1"/>
        </div>
      </div>
      <div class="row select-4col">
        <div>
          <label>Berat Barang (kg)</label>
          <input name="berat_barang" step="0.1" type="number" value="<?= htmlspecialchars($data['berat'] ?? '') ?>" required placeholder="Contoh: 1.5"/>
        </div>
        <div>
          <label>Panjang (cm)</label>
          <input name="panjang" type="number" min="0" value="<?= htmlspecialchars($data['panjang'] ?? '') ?>" placeholder="Contoh: 10"/>
        </div>
        <div>
          <label>Lebar (cm)</label>
          <input name="lebar" type="number" min="0" value="<?= htmlspecialchars($data['lebar'] ?? '') ?>" placeholder="Contoh: 10"/>
        </div>
        <div>
          <label>Tinggi (cm)</label>
          <input name="tinggi" type="number" min="0" value="<?= htmlspecialchars($data['tinggi'] ?? '') ?>" placeholder="Contoh: 10"/>
        </div>
      </div>
      <div class="row">
        <div>
          <label>Layanan Pengiriman</label>
          <select name="layanan_pengiriman" required>
            <option value="">Pilih</option>
            <option value="yes" <?= ($data['layanan_pengiriman'] ?? '') === 'yes' ? 'selected' : '' ?>>YES</option>
            <option value="reguler" <?= ($data['layanan_pengiriman'] ?? '') === 'reguler' ? 'selected' : '' ?>>Reguler</option>
          </select>
        </div>
        <div>
          <label>Pilih Kurir</label>
          <select name="kurir" id="kurir">
            <option value="">Pilih Kurir</option>
            <option value="jne" <?= ($data['kurir'] ?? '') === 'jne' ? 'selected' : '' ?>>JNE</option>
            <option value="jnt" <?= ($data['kurir'] ?? '') === 'jnt' ? 'selected' : '' ?>>J&T</option>
            <option value="sicepat" <?= ($data['kurir'] ?? '') === 'sicepat' ? 'selected' : '' ?>>SiCepat</option>
          </select>
        </div>
        <div>
          <label>Cek Ongkir</label>
          <button type="button" id="cekOngkir">Cek Ongkir</button>
        </div>
      </div>
      <div id="ratesOptions" style="display:none;">
        <select name="biaya_ongkir" id="biaya_ongkir">
          <option value="">Pilih...</option>
        </select>
      </div>
      <div class="row">
        <div>
          <label>Metode Pembayaran</label>
          <select name="metode_pembayaran" required>
            <option value="">Pilih</option>
            <option value="cod" <?= ($data['metode_pembayaran'] ?? '') === 'cod' ? 'selected' : '' ?>>COD (Bayar di Tujuan)</option>
            <option value="transfer" <?= ($data['metode_pembayaran'] ?? '') === 'transfer' ? 'selected' : '' ?>>Transfer</option>
            <option value="bayar_di_kantor" <?= ($data['metode_pembayaran'] ?? '') === 'bayar_di_kantor' ? 'selected' : '' ?>>Bayar di Kantor JNE</option>
          </select>
        </div>
      </div>

      <!-- Bagian HTML untuk checkbox asuransi dan nilai barang -->
      <div class="checkbox-group">
        <label>
          <input name="opsi_packing" type="checkbox" <?= isset($data['packing_kayu']) && $data['packing_kayu'] ? 'checked' : '' ?>>Packing Kayu
        </label>
        <label>
          <input name="asuransi" id="asuransiCheckbox" type="checkbox" <?= isset($data['asuransi']) && $data['asuransi'] ? 'checked' : '' ?>>Tambahkan Asuransi
        </label>
        <small style="display: block; margin: 6px 0 12px 28px; color: #666;">
          Centang ini jika Anda ingin barang diasuransikan. Biaya asuransi akan dihitung dari nilai barang.
        </small>
        <div id="nilaiBarangInput" style="display:<?= isset($data['asuransi']) && $data['asuransi'] ? 'block' : 'none' ?>;">
          <label>Nilai Barang (Rp)</label>
          <input type="number" name="nilai_barang" min="0" step="1000" value="<?= htmlspecialchars($data['nilai_barang'] ?? '') ?>" placeholder="Contoh: 200000"/>
          <small style="color: #666;">
            Masukkan perkiraan harga barang. Biaya asuransi = 0.2% dari nilai barang + Rp5.000.
          </small>
        </div>
      </div>

      <h2>Bagaimana Saya Akan Menyerahkan Paket Ini?</h2>
      <div class="checkbox-group">
        <label>
          <input name="penyerahan" type="radio" value="antar" <?= ($data['metode_penyerahan'] ?? 'antar') === 'antar' ? 'checked' : '' ?>>Saya akan mengantar paket ke kantor
        </label>
        <label>
          <input name="penyerahan" type="radio" value="jemput" <?= ($data['metode_penyerahan'] ?? 'antar') === 'jemput' ? 'checked' : '' ?>>Tolong jemput paket ini di alamat saya
        </label>
      </div>

      <div class="footer-total">
        <div class="left">
          Total Biaya
        </div>
        <div class="right">
          <div class="price-link">
            <div class="price" id="totalHarga">
              Rp <?= number_format($data['biaya'] ?? 0, 2, ',', '.') ?>
            </div>
            <a href="#" id="lihatDetailBiaya"><strong>Lihat Detail Biaya</strong></a>
          </div>
          <input type="hidden" name="kode" id="inputKodePaket" value="<?= htmlspecialchars($kode) ?>">
          <button class="btn-lanjut" type="submit" disabled>Lanjut</button>
        </div>
      </div>
    </form>
    <script>
      function handleSubmit(event) {
        // The form will submit to simpanDetailPaket.php which saves data to DB
        // After successful save, simpanDetailPaket.php should redirect to tambahPaket page with incomplete package card shown
        // Here we just allow the form to submit normally
        return true;
      }
    </script>

    <!-- Popup untuk Rates -->
    <div class="popup" id="ratesPopup">
      <div class="popup-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <h2>Pilih Layanan & Ongkir</h2>
        <table>
          <thead>
            <tr>
              <th>Layanan</th>
              <th>Harga</th>
              <th>Estimasi</th>
              <th>Pilih</th>
            </tr>
          </thead>
          <tbody id="ratesTableBody"></tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
document.addEventListener('DOMContentLoaded', function() {
    // Validasi nomor HP dan field wajib sebelum submit formulir utama
    const form = document.getElementById('formPengiriman');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validasi field wajib menggunakan checkFormValidity
            if (!checkFormValidity()) {
                e.preventDefault();
                alert('Please fill at least sender or receiver information to proceed.');
                return;
            }

            let noHpPengirim = document.querySelector('input[name="no_hp_pengirim"]').value.trim();
            let noHpPenerima = document.querySelector('input[name="no_hp_penerima"]').value.trim();

            // Remove non-digit characters before validation
            noHpPengirim = noHpPengirim.replace(/\D/g, '');
            noHpPenerima = noHpPenerima.replace(/\D/g, '');

            const numRegex = /^0\d{9,13}$/; // Must start with 0, 10-14 digits

            console.log('Validating:', { noHpPengirim, noHpPenerima }); // Debug
            if (!numRegex.test(noHpPengirim)) {
                e.preventDefault();
                alert('Nomor HP pengirim harus dimulai dengan 0 dan berisi 10-14 digit');
                return;
            }
            if (!numRegex.test(noHpPenerima)) {
                e.preventDefault();
                alert('Nomor HP penerima harus dimulai dengan 0 dan berisi 10-14 digit');
                return;
            }

            // Prevent submit if modal is open
            const modal = document.getElementById('formModal');
            if (modal && modal.style.display === 'block') {
                e.preventDefault();
                alert('Silakan simpan data pengirim/penerima terlebih dahulu di form modal.');
                return;
            }
        });
    } else {
        console.error('Elemen #formPengiriman tidak ditemukan di DOM');
    }

    // Fungsi bukaForm
    window.bukaForm = function(tipe, mode) {
        const modal = document.getElementById('formModal');
        const title = document.getElementById('modalTitle');
        const formType = document.getElementById('formType');
        const penerimaFields = document.getElementById('penerimaFields');
        const nama = modal.querySelector('#nama_pengirim, #nama_penerima');
        const hp = modal.querySelector('#no_hp_pengirim, #no_hp_penerima');
        const provinsi = modal.querySelector('#provinsi');
        const kota = modal.querySelector('#kota_kabupaten');
        const kecamatan = modal.querySelector('#kecamatan');
        const alamat = modal.querySelector('#detail_alamat_pengirim, #detail_alamat_penerima');
        const postal = modal.querySelector('#postal_pengirim, #postal_penerima');
        const pin_point = modal.querySelector('#pin_point_pengirim, #pin_point_penerima');

        formType.value = tipe;
        title.textContent = `Form ${tipe === 'pengirim' ? 'Alamat Pengirim' : 'Data Penerima'}`;
        penerimaFields.style.display = tipe === 'penerima' ? 'flex' : 'none';

        if (tipe === 'penerima') {
            nama.name = 'nama_penerima'; nama.id = 'nama_penerima';
            hp.name = 'no_hp_penerima'; hp.id = 'no_hp_penerima';
            alamat.name = 'detail_alamat_penerima'; alamat.id = 'detail_alamat_penerima';
            postal.name = 'postal_penerima'; postal.id = 'postal_penerima';
            pin_point.name = 'pin_point_penerima'; pin_point.id = 'pin_point_penerima';
        } else {
            nama.name = 'nama_pengirim'; nama.id = 'nama_pengirim';
            hp.name = 'no_hp_pengirim'; hp.id = 'no_hp_pengirim';
            alamat.name = 'detail_alamat_pengirim'; alamat.id = 'detail_alamat_pengirim';
            postal.name = 'postal_pengirim'; postal.id = 'postal_pengirim';
            pin_point.name = 'pin_point_pengirim'; pin_point.id = 'pin_point_pengirim';
        }

        if (mode === 'lama') {
            if (tipe === 'pengirim') {
                nama.value = '<?= htmlspecialchars($data['nama_pengirim'] ?? '') ?>';
                hp.value = '<?= htmlspecialchars($data['no_hp_pengirim'] ?? '') ?>';
                alamat.value = '<?= htmlspecialchars($data['detail_alamat_pengirim'] ?? '') ?>';
                postal.value = '<?= htmlspecialchars($data['postal_pengirim'] ?? '') ?>';
                pin_point.value = '<?= htmlspecialchars($data['pin_point_pengirim'] ?? '') ?>';
                provinsi.value = '';
                kota.value = '';
                kecamatan.value = '';
            } else {
                nama.value = '<?= htmlspecialchars($data['nama_penerima'] ?? '') ?>';
                hp.value = '<?= htmlspecialchars($data['no_hp_penerima'] ?? '') ?>';
                alamat.value = '<?= htmlspecialchars($data['detail_alamat_penerima'] ?? '') ?>';
                postal.value = '<?= htmlspecialchars($data['postal_penerima'] ?? '') ?>';
                pin_point.value = '<?= htmlspecialchars($data['pin_point_penerima'] ?? '') ?>';
                provinsi.value = '<?= htmlspecialchars($data['provinsi'] ?? '') ?>';
                kota.value = '<?= htmlspecialchars($data['kota_kabupaten'] ?? '') ?>';
                kecamatan.value = '<?= htmlspecialchars($data['kecamatan'] ?? '') ?>';
            }
        } else {
            nama.value = '';
            hp.value = '';
            alamat.value = '';
            postal.value = '';
            pin_point.value = '';
            provinsi.value = '';
            kota.value = '';
            kecamatan.value = '';
        }

        modal.style.display = 'block';

        const modalInputs = document.querySelectorAll('#formModal input, #formModal textarea, #formModal select');
        modalInputs.forEach(input => {
            input.addEventListener('input', updateButtonState);
            input.addEventListener('change', updateButtonState);
        });
    }

    // Fungsi tutupForm
    window.tutupForm = function() {
        const modal = document.getElementById('formModal');
        if (modal) {
            modal.style.display = 'none';
            const form = modal.querySelector('form');
            if (form) form.reset();
        }
    }


    // Fungsi checkFormValidity
    function checkFormValidity() {
        console.log('checkFormValidity called');
        // Check if either sender or receiver data is filled
        const senderFields = ['nama_pengirim', 'no_hp_pengirim', 'detail_alamat_pengirim', 'postal_pengirim'];
        const receiverFields = ['nama_penerima', 'no_hp_penerima', 'kecamatan', 'kota_kabupaten', 'provinsi', 'detail_alamat_penerima', 'postal_penerima'];

        const senderFilled = senderFields.some(name => {
            const input = document.querySelector(`[name="${name}"]`);
            return input && input.value.trim() !== '';
        });

        const receiverFilled = receiverFields.some(name => {
            const input = document.querySelector(`[name="${name}"]`);
            return input && input.value.trim() !== '';
        });

        // Check if package details are minimally filled (for incomplete submission)
        const packageFilled = (
            document.querySelector('[name="jenis_barang"]')?.value.trim() !== '' &&
            document.querySelector('[name="berat_barang"]')?.value.trim() !== ''
        );

        // Pin point fields are optional, so ignore them in validation

        // Logic:
        // If either sender or receiver data is filled, allow form submission (button active)
        // If neither sender nor receiver is filled, allow if package details are filled
        if ((senderFilled || receiverFilled)) {
            console.log('checkFormValidity result: true (sender/receiver filled)');
            return true;
        }

        // Check modal fields if modal is open
        if (document.getElementById('formModal').style.display === 'block') {
            const formType = document.getElementById('formType').value;
            const modalFields = formType === 'pengirim' ? ['nama_pengirim', 'no_hp_pengirim', 'detail_alamat_pengirim', 'postal_pengirim'] : ['nama_penerima', 'no_hp_penerima', 'detail_alamat_penerima', 'postal_penerima', 'provinsi', 'kota_kabupaten', 'kecamatan'];
            const modalFilled = modalFields.some(id => {
                const input = document.getElementById(id);
                return input && input.value.trim() !== '';
            });
            if (modalFilled) {
                console.log('checkFormValidity result: true (modal filled)');
                return true;
            }
        }

        // Otherwise, allow if package is filled
        const result = packageFilled;
        console.log('checkFormValidity result:', result);
        return result;
    }

    // Fungsi updateButtonState
    function updateButtonState() {
        console.log('updateButtonState called');
        const button = document.querySelector('.btn-lanjut');
        if (button) {
            button.disabled = !checkFormValidity();
        }
    }

    // Event listeners for barang fields
    const barangFields = [
        'jenis_barang', 'jumlah_barang', 'berat_barang', 'panjang', 'lebar', 'tinggi', 'layanan_pengiriman', 'kurir', 'metode_pembayaran'
    ];
    barangFields.forEach(name => {
        const el = document.querySelector(`[name="${name}"]`);
        if (el) {
            el.addEventListener('input', updateButtonState);
            el.addEventListener('change', updateButtonState);
        }
    });

    // Fungsi saveModalData
    window.saveModalData = function() {
        const formType = document.getElementById('formType').value;
        const namaId = formType === 'pengirim' ? 'nama_pengirim' : 'nama_penerima';
        const hpId = formType === 'pengirim' ? 'no_hp_pengirim' : 'no_hp_penerima';
        const alamatId = formType === 'pengirim' ? 'detail_alamat_pengirim' : 'detail_alamat_penerima';
        const postalId = formType === 'pengirim' ? 'postal_pengirim' : 'postal_penerima';
        const pinPointId = formType === 'pengirim' ? 'pin_point_pengirim' : 'pin_point_penerima';

        const nama = document.getElementById(namaId).value.trim();
        let noHp = document.getElementById(hpId).value.trim();
        const alamat = document.getElementById(alamatId).value.trim();
        const postal = document.getElementById(postalId).value.trim();
        const pin_point = document.getElementById(pinPointId).value.trim();
        const provinsi = document.getElementById('provinsi').value.trim();
        const kota = document.getElementById('kota_kabupaten').value.trim();
        const kecamatan = document.getElementById('kecamatan').value.trim();

        if (!nama || !noHp || !alamat || !postal) {
            alert('Semua field wajib diisi.');
            return;
        }

        // Remove non-digit characters from noHp before validation
        noHp = noHp.replace(/\D/g, '');

        // Validasi nomor HP modal
        const numRegex = /^0\d{9,13}$/; // Must start with 0, 10-14 digits
        const hpLabel = formType === 'pengirim' ? 'pengirim' : 'penerima';

        if (!numRegex.test(noHp)) {
            alert(`Nomor HP ${hpLabel} harus dimulai dengan 0 dan berisi 10-14 digit`);
            return;
        }

        // Update hidden inputs in main form
        if (formType === 'pengirim') {
            document.querySelector('input[name="nama_pengirim"]').value = nama;
            document.querySelector('input[name="no_hp_pengirim"]').value = noHp;
            document.querySelector('input[name="detail_alamat_pengirim"]').value = alamat;
            document.querySelector('input[name="postal_pengirim"]').value = postal;
            document.querySelector('input[name="pin_point_pengirim"]').value = pin_point;
            // Update status box
            document.getElementById('pengirimStatus').innerHTML = `${nama}<br>${noHp}<br>${alamat}<br>Kode Pos: ${postal}<br>Pin Point: <a href="${pin_point}" target="_blank">Lihat Peta</a>`;
            document.getElementById('pengirimStatus').classList.add('active');
        } else if (formType === 'penerima') {
            document.querySelector('input[name="nama_penerima"]').value = nama;
            document.querySelector('input[name="no_hp_penerima"]').value = noHp;
            document.querySelector('input[name="kecamatan"]').value = kecamatan;
            document.querySelector('input[name="kota_kabupaten"]').value = kota;
            document.querySelector('input[name="provinsi"]').value = provinsi;
            document.querySelector('input[name="detail_alamat_penerima"]').value = alamat;
            document.querySelector('input[name="postal_penerima"]').value = postal;
            document.querySelector('input[name="pin_point_penerima"]').value = pin_point;
            // Update status box
            document.getElementById('penerimaStatus').innerHTML = `${nama}<br>${noHp}<br>${kecamatan}, ${kota}, ${provinsi}<br>${alamat}<br>Kode Pos: ${postal}<br>Pin Point: <a href="${pin_point}" target="_blank">Lihat Peta</a>`;
            document.getElementById('penerimaStatus').classList.add('active');
        }

        tutupForm();
        updateButtonState();
    }

    // Cek ongkir and other JS code...
    const btnCek = document.getElementById('cekOngkir');
    const ratesDiv = document.getElementById('ratesOptions');
    const selectRates = document.getElementById('biaya_ongkir');
    const totalHarga = document.getElementById('totalHarga');
    const ratesPopup = document.getElementById('ratesPopup');
    const ratesTableBody = document.getElementById('ratesTableBody');

    if (btnCek && ratesDiv && selectRates && totalHarga && ratesPopup && ratesTableBody) {
        btnCek.addEventListener('click', () => {
            const kurir = document.getElementById('kurir').value;
            const origin = document.querySelector('input[name="postal_pengirim"]').value;
            const dest = document.querySelector('input[name="postal_penerima"]').value;
            const weight = document.querySelector('input[name="berat_barang"]').value;
            const panjang = document.querySelector('input[name="panjang"]').value;
            const lebar = document.querySelector('input[name="lebar"]').value;
            const tinggi = document.querySelector('input[name="tinggi"]').value;

            if (!kurir || !origin || !dest || !weight || !panjang || !lebar || !tinggi) {
                alert('Isi kurir, postal code pengirim dan penerima, berat, panjang, lebar, dan tinggi dulu!');
                return;
            }

            fetch(`getShippingRates.php?origin=${origin}&dest=${dest}&weight=${weight * 1000}&length=${panjang}&width=${lebar}&height=${tinggi}&couriers=${kurir}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    selectRates.innerHTML = '<option value="">Pilih...</option>';
                    ratesTableBody.innerHTML = '';
                    data.pricing.forEach(rate => {
                        const opt = document.createElement('option');
                        opt.value = rate.price;
                        opt.textContent = `${rate.company} - ${rate.service_name} - Rp ${rate.price.toLocaleString('id-ID')} - ${rate.duration}`;
                        selectRates.appendChild(opt);

                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${rate.service_name}</td>
                            <td>Rp ${rate.price.toLocaleString('id-ID')}</td>
                            <td>${rate.duration}</td>
                            <td><button class="btn-select" data-price="${rate.price}" data-service="${rate.company} - ${rate.service_name}">Pilih</button></td>
                        `;
                        ratesTableBody.appendChild(row);
                    });
                    ratesDiv.style.display = 'block';
                    ratesPopup.style.display = 'flex';
                    document.querySelectorAll('.btn-select').forEach(btn => {
                        btn.addEventListener('click', () => {
                            selectRates.value = btn.dataset.price;
                            updateTotal();
                            closePopup();
                        });
                    });
                    updateTotal();
                } else {
                    alert(data.message);
                }
            })
            .catch(err => alert('Error fetch ongkir: ' + err));
        });
    } else {
        console.error('Salah satu elemen cek ongkir tidak ditemukan:', { btnCek, ratesDiv, selectRates, totalHarga, ratesPopup, ratesTableBody });
    }

    function closePopup() {
        const ratesPopup = document.getElementById('ratesPopup');
        if (ratesPopup) {
            ratesPopup.style.display = 'none';
        } else {
            console.error('Elemen #ratesPopup tidak ditemukan');
        }
    }

    // Update total saat pilih rates atau checkbox
    if (selectRates) {
        selectRates.addEventListener('change', updateTotal);
    }
    const elements = document.querySelectorAll('input[type="checkbox"], input[type="number"], select[name="metode_pembayaran"]');
    if (elements.length > 0) {
        elements.forEach(el => el.addEventListener('change', updateTotal));
    }

    function updateTotal() {
        let biaya = parseInt(selectRates?.value) || 0;
        const metode = document.querySelector('select[name="metode_pembayaran"]')?.value;
        const cod = metode === 'cod' ? biaya * 0.05 : 0;
        const asuransi = document.querySelector('input[name="asuransi"]')?.checked;
        const nilai = parseInt(document.querySelector('input[name="nilai_barang"]')?.value) || 0;
        const biayaAsuransi = asuransi ? Math.ceil(nilai * 0.002) + 5000 : 0;
        biaya += cod + biayaAsuransi;
        if (totalHarga) {
            totalHarga.textContent = `Rp ${biaya.toLocaleString('id-ID')}`;
        }
    }

    // Asuransi
    const asuransiCheckbox = document.querySelector('input[name="asuransi"]');
    const nilaiBarangDiv = document.getElementById('nilaiBarangInput');
    if (!asuransiCheckbox) {
        console.error('Elemen <input name="asuransi"> tidak ditemukan di DOM.');
    }
    if (!nilaiBarangDiv) {
        console.error('Elemen <div id="nilaiBarangInput"> tidak ditemukan di DOM.');
    } else if (asuransiCheckbox) {
        function toggleNilaiBarang() {
            nilaiBarangDiv.style.display = asuransiCheckbox.checked ? 'block' : 'none';
            if (typeof updateTotal === 'function') updateTotal();
        }
        asuransiCheckbox.addEventListener('change', toggleNilaiBarang);
        toggleNilaiBarang(); // Panggil sekali saat halaman dimuat
    }

    // Ambil kode dari GET jika ada, atau dari sessionStorage, atau generate baru
    const urlParams = new URLSearchParams(window.location.search);
    let kodePaket = urlParams.get('kode');

    if (!kodePaket) {
        // Cek sessionStorage
        kodePaket = sessionStorage.getItem('kodePaket');
        if (kodePaket) {
            const kodePaketElement = document.getElementById('kodePaket');
            const inputKodePaket = document.getElementById('inputKodePaket');
            if (kodePaketElement && inputKodePaket) {
                kodePaketElement.textContent = kodePaket;
                inputKodePaket.value = kodePaket;
            } else {
                console.error('Elemen #kodePaket atau #inputKodePaket tidak ditemukan');
            }
        } else {
            // Generate baru dan simpan di sessionStorage
            fetch('simpanPaket.php?action=generateKode')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'ok') {
                    kodePaket = data.kode;
                    sessionStorage.setItem('kodePaket', kodePaket);
                    const kodePaketElement = document.getElementById('kodePaket');
                    const inputKodePaket = document.getElementById('inputKodePaket');
                    if (kodePaketElement && inputKodePaket) {
                        kodePaketElement.textContent = kodePaket;
                        inputKodePaket.value = kodePaket;
                    } else {
                        console.error('Elemen #kodePaket atau #inputKodePaket tidak ditemukan');
                    }
                } else {
                    alert('Gagal generate kode: ' + data.message);
                }
            })
            .catch(err => alert('Error: ' + err));
        }
    } else {
        const kodePaketElement = document.getElementById('kodePaket');
        const inputKodePaket = document.getElementById('inputKodePaket');
        if (kodePaketElement && inputKodePaket) {
            kodePaketElement.textContent = kodePaket;
            inputKodePaket.value = kodePaket;
        } else {
            console.error('Elemen #kodePaket atau #inputKodePaket tidak ditemukan');
        }
    }

    // Tampilkan status box jika data ada
    if ('<?= $data['nama_pengirim'] ?? '' ?>') {
        const pengirimStatus = document.getElementById('pengirimStatus');
        if (pengirimStatus) {
            pengirimStatus.classList.add('active');
        } else {
            console.error('Elemen #pengirimStatus tidak ditemukan');
        }
    }
    if ('<?= $data['nama_penerima'] ?? '' ?>') {
        const penerimaStatus = document.getElementById('penerimaStatus');
        if (penerimaStatus) {
            penerimaStatus.classList.add('active');
        } else {
            console.error('Elemen #penerimaStatus tidak ditemukan');
        }
    }

    // Fungsi bukaAddressModal
    window.bukaAddressModal = function(type) {
        const modal = document.getElementById('addressModal');
        const title = document.getElementById('addressModalTitle');
        const list = document.getElementById('addressList');

        title.textContent = `Pilih Alamat ${type === 'pengirim' ? 'Pengirim' : 'Penerima'} Tersimpan`;
        list.innerHTML = '<p>Loading...</p>';
        modal.style.display = 'flex';

        fetch(`getAddresses.php?type=${type}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    list.innerHTML = '';
                    data.addresses.forEach(addr => {
                        const div = document.createElement('div');
                        div.className = 'address-item';
                        div.style.padding = '10px';
                        div.style.border = '1px solid #ddd';
                        div.style.marginBottom = '10px';
                        div.style.cursor = 'pointer';
                        div.innerHTML = `
                            <strong>${addr.nama}</strong><br>
                            ${addr.no_hp}<br>
                            ${type === 'penerima' ? `${addr.kecamatan}, ${addr.kota_kabupaten}, ${addr.provinsi}<br>` : ''}
                            ${addr.detail_alamat}<br>
                            Kode Pos: ${addr.postal}<br>
                            <a href="${addr.pin_point}" target="_blank">Lihat Peta</a>
                        `;
                        div.addEventListener('click', () => selectAddress(type, addr));
                        list.appendChild(div);
                    });
                } else {
                    list.innerHTML = '<p>Tidak ada alamat tersimpan.</p>';
                }
            })
            .catch(err => {
                list.innerHTML = '<p>Error loading addresses.</p>';
                console.error(err);
            });
    }

    // Fungsi tutupAddressModal
    window.tutupAddressModal = function() {
        const modal = document.getElementById('addressModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // Fungsi selectAddress
    function selectAddress(type, addr) {
        if (type === 'pengirim') {
            document.querySelector('input[name="nama_pengirim"]').value = addr.nama;
            document.querySelector('input[name="no_hp_pengirim"]').value = addr.no_hp;
            document.querySelector('input[name="detail_alamat_pengirim"]').value = addr.detail_alamat;
            document.querySelector('input[name="postal_pengirim"]').value = addr.postal;
            document.querySelector('input[name="pin_point_pengirim"]').value = addr.pin_point;
            document.getElementById('pengirimStatus').innerHTML = `${addr.nama}<br>${addr.no_hp}<br>${addr.detail_alamat}<br>Kode Pos: ${addr.postal}<br>Pin Point: <a href="${addr.pin_point}" target="_blank">Lihat Peta</a>`;
            document.getElementById('pengirimStatus').classList.add('active');
        } else if (type === 'penerima') {
            document.querySelector('input[name="nama_penerima"]').value = addr.nama;
            document.querySelector('input[name="no_hp_penerima"]').value = addr.no_hp;
            document.querySelector('input[name="kecamatan"]').value = addr.kecamatan;
            document.querySelector('input[name="kota_kabupaten"]').value = addr.kota_kabupaten;
            document.querySelector('input[name="provinsi"]').value = addr.provinsi;
            document.querySelector('input[name="detail_alamat_penerima"]').value = addr.detail_alamat;
            document.querySelector('input[name="postal_penerima"]').value = addr.postal;
            document.querySelector('input[name="pin_point_penerima"]').value = addr.pin_point;
            document.getElementById('penerimaStatus').innerHTML = `${addr.nama}<br>${addr.no_hp}<br>${addr.kecamatan}, ${addr.kota_kabupaten}, ${addr.provinsi}<br>${addr.detail_alamat}<br>Kode Pos: ${addr.postal}<br>Pin Point: <a href="${addr.pin_point}" target="_blank">Lihat Peta</a>`;
            document.getElementById('penerimaStatus').classList.add('active');
        }
        tutupAddressModal();
        updateButtonState();
    }

    // Initialize button state on page load
    updateButtonState();
});

</script>

</body>
</html>
