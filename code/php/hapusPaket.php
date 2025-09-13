<?php
ob_start();
session_start();
include "koneksi.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['kode'])) {
    ob_end_clean();
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$kode = $_POST['kode'];
if (!preg_match('/^PKT-\d{4}$/', $kode)) {
    ob_end_clean();
    echo json_encode(['status' => 'error', 'message' => 'Format kode tidak valid']);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM paket WHERE kodePaket = ?");
$stmt->bind_param("s", $kode);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    ob_end_clean();
    echo json_encode(['status' => 'error', 'message' => 'Paket tidak ditemukan']);
    $stmt->close();
    exit;
}
$stmt->close();

$stmt = $conn->prepare("INSERT INTO log (aksi, kodePaket, era_waktu) VALUES ('hapus', ?, NOW())");
$stmt->bind_param("s", $kode);
if (!$stmt->execute()) {
    ob_end_clean();
    echo json_encode(['status' => 'error', 'message' => 'Gagal log: ' . $stmt->error]);
    $stmt->close();
    exit;
}
$stmt->close();

$stmt = $conn->prepare("DELETE FROM paket WHERE kodePaket = ?");
$stmt->bind_param("s", $kode);
if ($stmt->execute() && $stmt->affected_rows > 0) {
    $conn->commit(); // Ensure transaction is committed
    if (isset($_SESSION['paket'][$kode])) {
        unset($_SESSION['paket'][$kode]);
    }
    ob_end_clean();
    echo json_encode(['status' => 'ok', 'message' => 'Paket berhasil dihapus', 'kode' => $kode]);
} else {
    ob_end_clean();
    echo json_encode(['status' => 'error', 'message' => 'Gagal hapus: ' . $stmt->error]);
}
$stmt->close();
$conn->close();
?>
