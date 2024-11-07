<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data pendanaan sebelum dihapus
    $stmt = $conn->prepare("SELECT * FROM jenis_pendanaan WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pendanaan = $result->fetch_assoc();

    if ($pendanaan) {
        $stmt = $conn->prepare("INSERT INTO riwayat_penghapusan (jenis_pendanaan_id, nama_pendanaan, deskripsi_singkat, nominal, code_name, tanggal_rilis, expiry, tipe_pendanaan, deleted_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("isssssss", 
            $pendanaan['id'], 
            $pendanaan['namaPendanaan'], 
            $pendanaan['deskripsiSingkat'], 
            $pendanaan['nominal'], 
            $pendanaan['codeName'], 
            $pendanaan['tanggalRilis'], 
            $pendanaan['expiry'], 
            $pendanaan['tipePendanaan']
        );

        if ($stmt->execute()) {
            $riwayatId = $stmt->insert_id;

            if ($pendanaan['tipePendanaan'] === 'khusus') {
                $detailStmt = $conn->prepare("SELECT * FROM pendanaan_khusus WHERE id_pendanaan = ?");
                $detailStmt->bind_param("i", $id);
                $detailStmt->execute();
                $detailsResult = $detailStmt->get_result();

                while ($detailRow = $detailsResult->fetch_assoc()) {
                    $insertDetailStmt = $conn->prepare("INSERT INTO riwayat_pendanaan_khusus (riwayat_penghapusan_id, jenjang_id, klasifikasi_id, jabatan_id) VALUES (?, ?, ?, ?)");
                    $insertDetailStmt->bind_param("iiii", $riwayatId, $detailRow['id_jenjang'], $detailRow['id_klasifikasi'], $detailRow['id_jabatan']);
                    $insertDetailStmt->execute();
                    $insertDetailStmt->close();
                }

                $deleteDetailStmt = $conn->prepare("DELETE FROM pendanaan_khusus WHERE id_pendanaan = ?");
                $deleteDetailStmt->bind_param("i", $id);
                $deleteDetailStmt->execute();
                $deleteDetailStmt->close();
            }

            $deleteStmt = $conn->prepare("DELETE FROM jenis_pendanaan WHERE id = ?");
            $deleteStmt->bind_param("i", $id);

            if ($deleteStmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error deleting record: ' . $conn->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error inserting to riwayat_penghapusan: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
