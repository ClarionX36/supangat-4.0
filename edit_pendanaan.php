<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Ambil ID dari GET
    $id = $_GET['id'];

    // Ambil data dari database berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM jenis_pendanaan WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pendanaan = $result->fetch_assoc();

    // Jika data ditemukan, ambil data
    if ($pendanaan) {
        $namaPendanaan = $pendanaan['namaPendanaan'];
        $deskripsiSingkat = $pendanaan['deskripsiSingkat'];
        $nominal = $pendanaan['nominal'];
        $codeName = $pendanaan['codeName'];
        $tanggalRilis = $pendanaan['tanggalRilis'];
        $expiry = $pendanaan['expiry'];
    } else {
        echo "Data tidak ditemukan.";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil ID dari POST
    $id = $_POST['id'];

    // Ambil data dari form
    $namaPendanaan = $_POST['namaPendanaan'] ?? '';
    $deskripsiSingkat = $_POST['deskripsiSingkat'] ?? '';
    $nominal = str_replace(',', '', $_POST['nominal'] ?? '0');
    $codeName = $_POST['codeName'] ?? '';
    $tanggalRilis = $_POST['tanggalRilis'] ?? '';
    $expiry = $_POST['expiry'] ?? '';

    // Menggunakan Prepared Statement
    $stmt = $conn->prepare("UPDATE jenis_pendanaan 
                             SET namaPendanaan = ?, deskripsiSingkat = ?, nominal = ?, codeName = ?, expiry = ?, tanggalRilis = ?
                             WHERE id = ?");
    $stmt->bind_param("ssisssi", $namaPendanaan, $deskripsiSingkat, $nominal, $codeName, $expiry, $tanggalRilis, $id);

    if ($stmt->execute()) {
        header("Location: index.php?status=edit_success");
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();
?>
