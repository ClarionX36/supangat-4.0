<?php
include 'koneksi.php';

$tipePendanaan = $_POST['tipePendanaan'] ?? '';
$namaPendanaan = $_POST['namaPendanaan'] ?? '';
$deskripsiSingkat = $_POST['deskripsiSingkat'] ?? '';
$nominal = str_replace(',', '', $_POST['nominal'] ?? '0');
$codeName = $_POST['codeName'] ?? '';
$expiry = $_POST['expiry'] ?? '';
$tanggalRilis = $_POST['tanggalRilis'] ?? '';

// Format tanggal jika kosong
if (empty($tanggalRilis)) {
    $tanggalRilis = date('Y-m-d');  // Set default ke tanggal hari ini jika kosong
}

// Validasi input utama
if (empty($tipePendanaan) || empty($namaPendanaan) || empty($nominal) || empty($codeName)) {
    echo "Semua field harus diisi.";
    exit;
}

// Simpan ke tabel jenis_pendanaan
$stmt = $conn->prepare("INSERT INTO jenis_pendanaan (tipePendanaan, namaPendanaan, deskripsiSingkat, nominal, codeName, expiry, tanggalRilis)
                         VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $tipePendanaan, $namaPendanaan, $deskripsiSingkat, $nominal, $codeName, $expiry, $tanggalRilis);

if ($stmt->execute()) {
    $pendanaanId = $conn->insert_id; // Dapatkan ID pendanaan yang baru disimpan

    // Jika pendanaan khusus, simpan data pilihan jenjang, klasifikasi, jabatan
    if ($tipePendanaan === 'khusus') {
        // Simpan data pilihan jenjang
        if (!empty($_POST['jenjang'])) {
            foreach ($_POST['jenjang'] as $jenjangId) {
                $insertJenjang = $conn->prepare("INSERT INTO pendanaan_khusus (id_pendanaan, id_jenjang, id_klasifikasi, id_jabatan) VALUES (?, ?, NULL, NULL)");
                $insertJenjang->bind_param("ii", $pendanaanId, $jenjangId);
                if (!$insertJenjang->execute()) {
                    echo "Error (jenjang): " . $insertJenjang->error;
                    exit;
                }
                $insertJenjang->close();
            }
        }

        // Simpan data pilihan klasifikasi
        if (!empty($_POST['klasifikasi'])) {
            foreach ($_POST['klasifikasi'] as $klasifikasiId) {
                $insertKlasifikasi = $conn->prepare("INSERT INTO pendanaan_khusus (id_pendanaan, id_jenjang, id_klasifikasi, id_jabatan) VALUES (?, NULL, ?, NULL)");
                $insertKlasifikasi->bind_param("ii", $pendanaanId, $klasifikasiId);
                if (!$insertKlasifikasi->execute()) {
                    echo "Error (klasifikasi): " . $insertKlasifikasi->error;
                    exit;
                }
                $insertKlasifikasi->close();
            }
        }

        // Simpan data pilihan jabatan
        if (!empty($_POST['jabatan'])) {
            foreach ($_POST['jabatan'] as $jabatanId) {
                $insertJabatan = $conn->prepare("INSERT INTO pendanaan_khusus (id_pendanaan, id_jenjang, id_klasifikasi, id_jabatan) VALUES (?, NULL, NULL, ?)");
                $insertJabatan->bind_param("ii", $pendanaanId, $jabatanId);
                if (!$insertJabatan->execute()) {
                    echo "Error (jabatan): " . $insertJabatan->error;
                    exit;
                }
                $insertJabatan->close();
            }
        }
    }

    // Redirect ke halaman index dengan status success
    header("Location: index.php?status=success");
    exit();
} else {
    // Jika terjadi error di jenis_pendanaan
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
